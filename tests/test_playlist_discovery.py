"""End-to-end playlist discovery tests; requires PHP CLI and Python 3."""
import functools
import http.server
import os
from pathlib import Path
import shutil
import subprocess
import tempfile
import threading
import unittest

APP = Path(__file__).resolve().parents[1] / "m3u-playlist-cleaner"


class QuietHandler(http.server.SimpleHTTPRequestHandler):
    def log_message(self, *_args):
        pass


class PlaylistDiscoveryTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        if not shutil.which("php"):
            raise RuntimeError("PHP CLI is required to run these regression tests")
        cls.stream_dir = tempfile.TemporaryDirectory()
        Path(cls.stream_dir.name, "stream").write_bytes(b"x" * 2048)
        handler = functools.partial(QuietHandler, directory=cls.stream_dir.name)
        cls.server = http.server.ThreadingHTTPServer(("127.0.0.1", 0), handler)
        cls.thread = threading.Thread(target=cls.server.serve_forever, daemon=True)
        cls.thread.start()

    @classmethod
    def tearDownClass(cls):
        cls.server.shutdown()
        cls.server.server_close()
        cls.thread.join()
        cls.stream_dir.cleanup()

    def run_validator(self, filenames):
        with tempfile.TemporaryDirectory() as tmp:
            root = Path(tmp)
            inputs = root / "inputs"
            inputs.mkdir()
            for index, filename in enumerate(filenames):
                (inputs / filename).write_text(
                    '#EXTM3U\n#EXTINF:-1,Channel%s\nhttp://127.0.0.1:%s/stream\n'
                    % (index, self.server.server_port), encoding="utf-8"
                )
            output = root / "result.m3u"
            env = dict(os.environ, M3U_DIRECTORY=str(inputs),
                       LOG_FILE=str(root / "validator.log"), OUTPUT_FILE=str(output))
            result = subprocess.run(
                ["php", "playlist_validator.php"], cwd=APP, env=env,
                capture_output=True, text=True, timeout=20
            )
            self.assertEqual(result.returncode, 0, result.stdout + result.stderr)
            self.assertEqual(result.stderr, "", result.stderr)
            return output.read_text() if output.exists() else None, result.stdout

    def test_m3u_only(self):
        output, log = self.run_validator(["channels.m3u"])
        self.assertIn("Channel0", output)
        self.assertIn("Playlist written successfully", log)

    def test_m3u8_only(self):
        output, log = self.run_validator(["channels.m3u8"])
        self.assertIn("Channel0", output)
        self.assertIn("Playlist written successfully", log)

    def test_mixed_extensions_and_unrelated_files(self):
        output, _ = self.run_validator(
            ["b.m3u", "a.m3u8", "ignored.txt", "backup.m3u8.bak", "other.m3u9"]
        )
        self.assertTrue(output.startswith("#EXTM3U\n"))
        self.assertIn("Channel0", output)
        self.assertIn("Channel1", output)
        self.assertEqual(output.count("#EXTINF"), 2)

    def test_empty_directory(self):
        output, log = self.run_validator([])
        self.assertIsNone(output)
        self.assertIn("No channels available to write to the playlist.", log)

    def test_unrelated_files_only(self):
        output, log = self.run_validator(["ignored.txt", "backup.m3u8.bak"])
        self.assertIsNone(output)
        self.assertIn("No channels available to write to the playlist.", log)


if __name__ == "__main__":
    unittest.main()
