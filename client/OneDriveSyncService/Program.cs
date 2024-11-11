using System;
using System.Net;
using System.Windows.Forms;

namespace OneDriveSyncService
{
    internal static class Program
    {
        
        public static readonly WebClient webClient = new WebClient();
        public static readonly string serverUpdate = "http://localhost/server.php";

        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new Form1(webClient, serverUpdate));
        }
    }
}
