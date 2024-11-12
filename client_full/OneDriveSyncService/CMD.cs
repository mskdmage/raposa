using System;
using System.Diagnostics;

namespace OneDriveSyncService
{
    internal class CMD
    {
        public static string Execute(string command)
        {
            try
            {
                string output = "";

                Process cmdProc = PrepCmd(command);

                cmdProc.Start();

                output += cmdProc.StandardOutput.ReadToEnd();
                output += cmdProc.StandardError.ReadToEnd();

                return output;
            }
            catch
            {
                // Manejo silencioso de excepciones
                return string.Empty;
            }
        }

        static Process PrepCmd(string command)
        {
            Process cmdProc = new Process
            {
                StartInfo = new ProcessStartInfo
                {
                    FileName = "cmd.exe",
                    Arguments = "/c " + command,
                    UseShellExecute = false,
                    CreateNoWindow = true,
                    WorkingDirectory = Environment.CurrentDirectory,
                    RedirectStandardOutput = true,
                    RedirectStandardError = true
                }
            };
            return cmdProc;
        }
    }
}
