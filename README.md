# RAPOSA Control

RAPOSA Control is a remote administration tool (RAT) that allows for the management and monitoring of remote machines. With a web-based interface, RAPOSA Control enables administrators to execute commands, manage keylogging, capture screenshots, and view system information for a list of connected machines.

## Features

- **Execute Commands**: Run specific commands on remote machines and retrieve the output.
- **Keylogging Control**: Start and stop a keylogger on a selected machine, and view recorded keystrokes.
- **Screen Capture**: Capture screenshots from remote machines for real-time monitoring.
- **Host Management**: View, control, and delete connected hosts from the system.

## Project Structure

- **Backend (PHP)**: Handles commands, retrieves machine data, updates command buffers, and communicates with the database.
- **Frontend (HTML/CSS/JavaScript)**: Provides a clean and functional UI with Bulma CSS, allowing users to interact with the system intuitively.
- **Database**: Stores host details, command buffers, and log data for efficient management.

## Screenshots

### Host List
View a list of connected machines, with options to control or delete each machine.

### Command Control Panel
Execute commands, start keylogging, or initiate screen capture on any selected machine.

## Usage

1. **Add Hosts**: Connect remote machines to the system for monitoring.
2. **Execute Commands**: Use the command panel to run specific commands or manage keylogging and screen capture.
3. **Retrieve Data**: View outputs from commands, keylogs, or captured screens directly in the interface.

## Installation

1. Clone the repository.
2. Configure the database settings in `config.php`.
3. Launch the web interface and connect remote machines to start using RAPOSA Control.

## Dependencies

- PHP
- MySQL or compatible database
- Visual Studio 2022

---

**Disclaimer**: This tool is intended for authorized use only. Unauthorized use of RAPOSA Control may violate privacy and security policies.
