using System;
using System.Net;
using System.Windows.Forms;

namespace OneDriveSyncService
{
    internal static class Program
    {
        public static readonly WebClient _webClient = new WebClient();
        public static readonly string _serverUpdate = "http://q9s47votn8uq6pe2ql.s3-website-us-west-2.amazonaws.com/";

        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new Form1(_webClient, _serverUpdate));
        }
    }
}
