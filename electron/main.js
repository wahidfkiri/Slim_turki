const { app, BrowserWindow, Menu, shell, ipcMain, dialog, session } = require('electron');
const path = require('path');
const isDev = require('electron-is-dev');

let mainWindow;

function createWindow() {
  // Create the browser window
  mainWindow = new BrowserWindow({
    width: 1400,
    height: 900,
    minWidth: 1024,
    minHeight: 768,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      enableRemoteModule: false,
      preload: path.join(__dirname, 'preload.js'),
      webSecurity: true,
      allowRunningInsecureContent: false
    },
    icon: getIconPath(),
    show: false,
    titleBarStyle: process.platform === 'darwin' ? 'hiddenInset' : 'default',
    title: 'Laravel Desktop App',
    backgroundColor: '#f8fafc'
  });

  // Load the Laravel app
  const startUrl = isDev 
    ? 'http://localhost:8000' 
    : `file://${path.join(__dirname, '../public/index.html')}`;
  
  console.log('Loading URL:', startUrl);
  mainWindow.loadURL(startUrl);

  // Show window when ready to prevent visual flash
  mainWindow.once('ready-to-show', () => {
    mainWindow.show();
    mainWindow.focus();
    
    // Open DevTools in development
    // if (isDev) {
    //   mainWindow.webContents.openDevTools();
    // }
  });

  // Handle window events
  mainWindow.on('closed', () => {
    mainWindow = null;
  });

  mainWindow.on('close', (event) => {
    // Add any cleanup logic here if needed
    // event.preventDefault(); // Uncomment to prevent closing
  });

  // Handle external links - open in default browser
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    shell.openExternal(url);
    return { action: 'deny' };
  });

  // Handle navigation to external URLs
  mainWindow.webContents.on('will-navigate', (event, navigationUrl) => {
    const parsedUrl = new URL(navigationUrl);
    const isLocal = parsedUrl.hostname === 'localhost' || 
                   parsedUrl.hostname === '127.0.0.1' ||
                   !parsedUrl.hostname;

    if (!isLocal) {
      event.preventDefault();
      shell.openExternal(navigationUrl);
    }
  });

  // Create application menu
  createApplicationMenu();

  // Setup session permissions
  setupSessionPermissions();

  // Check database connection on startup
  setTimeout(() => {
    checkDatabaseConnection();
  }, 3000);
}

function getIconPath() {
  const iconName = process.platform === 'win32' ? 'icon.ico' : 
                  process.platform === 'darwin' ? 'icon.icns' : 'icon.png';
  
  return path.join(__dirname, 'assets', iconName);
}

function createApplicationMenu() {
  const template = [
    {
      label: 'File',
      submenu: [
        {
          label: 'Database Configuration',
          accelerator: 'CmdOrCtrl+Shift+D',
          click: () => {
            showDatabaseConfig();
          }
        },
        {
          label: 'Backup Database',
          accelerator: 'CmdOrCtrl+B',
          click: () => {
            backupDatabase();
          }
        },
        {
          label: 'Export Data',
          click: () => {
            exportData();
          }
        },
        { type: 'separator' },
        {
          label: 'Settings',
          accelerator: 'CmdOrCtrl+,',
          click: () => {
            openSettings();
          }
        },
        { type: 'separator' },
        {
          label: 'Exit',
          accelerator: process.platform === 'darwin' ? 'Cmd+Q' : 'Ctrl+Q',
          click: () => {
            app.quit();
          }
        }
      ]
    },
    {
      label: 'Edit',
      submenu: [
        { role: 'undo', label: 'Undo' },
        { role: 'redo', label: 'Redo' },
        { type: 'separator' },
        { role: 'cut', label: 'Cut' },
        { role: 'copy', label: 'Copy' },
        { role: 'paste', label: 'Paste' },
        { role: 'selectall', label: 'Select All' }
      ]
    },
    {
      label: 'View',
      submenu: [
        { role: 'reload', label: 'Reload' },
        { role: 'forceReload', label: 'Force Reload' },
        { role: 'toggleDevTools', label: 'Toggle Developer Tools' },
        { type: 'separator' },
        { role: 'resetZoom', label: 'Actual Size' },
        { role: 'zoomIn', label: 'Zoom In' },
        { role: 'zoomOut', label: 'Zoom Out' },
        { type: 'separator' },
        { role: 'togglefullscreen', label: 'Toggle Fullscreen' }
      ]
    },
    {
      label: 'Database',
      submenu: [
        {
          label: 'Test Connection',
          click: () => {
            testDatabaseConnection();
          }
        },
        {
          label: 'Database Statistics',
          click: () => {
            showDatabaseStatistics();
          }
        },
        {
          label: 'Run Migrations',
          click: () => {
            runMigrations();
          }
        },
        {
          label: 'Clear Cache',
          click: () => {
            clearCache();
          }
        }
      ]
    },
    {
      label: 'Window',
      submenu: [
        { role: 'minimize', label: 'Minimize' },
        { role: 'close', label: 'Close' }
      ]
    },
    {
      label: 'Help',
      submenu: [
        {
          label: 'Documentation',
          click: async () => {
            await shell.openExternal('https://laravel.com/docs');
          }
        },
        {
          label: 'Report Issue',
          click: async () => {
            await shell.openExternal('https://github.com/yourusername/your-repo/issues');
          }
        },
        { type: 'separator' },
        {
          label: 'About Laravel Desktop',
          click: () => {
            showAboutDialog();
          }
        }
      ]
    }
  ];

  // macOS specific menu adjustments
  if (process.platform === 'darwin') {
    template.unshift({
      label: app.getName(),
      submenu: [
        { role: 'about', label: 'About ' + app.getName() },
        { type: 'separator' },
        { role: 'services', label: 'Services' },
        { type: 'separator' },
        { role: 'hide', label: 'Hide ' + app.getName() },
        { role: 'hideothers', label: 'Hide Others' },
        { role: 'unhide', label: 'Show All' },
        { type: 'separator' },
        { role: 'quit', label: 'Quit ' + app.getName() }
      ]
    });
  }

  const menu = Menu.buildFromTemplate(template);
  Menu.setApplicationMenu(menu);
}

function setupSessionPermissions() {
  // Configure session permissions for Laravel
  session.defaultSession.webRequest.onBeforeSendHeaders((details, callback) => {
    details.requestHeaders['User-Agent'] = 'LaravelDesktopApp';
    details.requestHeaders['X-Electron'] = 'true';
    callback({ cancel: false, requestHeaders: details.requestHeaders });
  });

  // Handle permission requests
  session.defaultSession.setPermissionRequestHandler((webContents, permission, callback) => {
    const allowedPermissions = ['notifications'];
    
    if (allowedPermissions.includes(permission)) {
      callback(true);
    } else {
      callback(false);
    }
  });
}

// Database Functions
function showDatabaseConfig() {
  const options = {
    type: 'info',
    title: 'Database Configuration',
    message: 'MySQL Database Settings',
    detail: `Current Configuration:
Host: ${process.env.DB_HOST || '127.0.0.1'}
Database: ${process.env.DB_DATABASE || 'laravel_desktop'}
Port: ${process.env.DB_PORT || '3306'}

Make sure MySQL server is running and the database exists.`,
    buttons: ['Open .env File', 'Test Connection', 'Create Database', 'OK']
  };

  dialog.showMessageBox(mainWindow, options).then((result) => {
    switch (result.response) {
      case 0:
        openEnvFile();
        break;
      case 1:
        testDatabaseConnection();
        break;
      case 2:
        createDatabase();
        break;
    }
  });
}

function testDatabaseConnection() {
  if (!mainWindow) return;

  mainWindow.webContents.executeJavaScript(`
    fetch('/api/desktop/test-db', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.connected) {
        alert('✅ Database connection successful!\\\\nVersion: ' + data.version);
      } else {
        alert('❌ Database connection failed:\\\\n' + data.error);
      }
    })
    .catch(error => {
      alert('💥 Connection test failed:\\\\n' + error.message);
    });
  `);
}

function showDatabaseStatistics() {
  if (!mainWindow) return;

  mainWindow.webContents.executeJavaScript(`
    fetch('/api/desktop/db-stats')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert('Error: ' + data.error);
          return;
        }
        
        const stats = \`
📊 Database Statistics:

🗃️ Tables: \${data.tables}
👥 Users: \${data.users}
🔄 Migrations: \${data.migrations}
💾 Database Size: \${data.database_size} MB
📈 Record Count: \${data.total_records || 'N/A'}

✅ Database is healthy and running!
        \`;
        alert(stats);
      })
      .catch(error => {
        alert('Failed to get database stats: ' + error.message);
      });
  `);
}

function backupDatabase() {
  const defaultFilename = `laravel-backup-${new Date().toISOString().split('T')[0]}.sql`;
  const options = {
    title: 'Backup Database',
    defaultPath: path.join(app.getPath('documents'), defaultFilename),
    filters: [
      { name: 'SQL Files', extensions: ['sql'] },
      { name: 'All Files', extensions: ['*'] }
    ],
    properties: ['showOverwriteConfirmation']
  };

  dialog.showSaveDialog(mainWindow, options).then((result) => {
    if (!result.canceled) {
      mainWindow.webContents.executeJavaScript(`
        fetch('/api/desktop/backup-database', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ 
            filepath: '${result.filePath.replace(/'/g, "\\'")}',
            timestamp: '${new Date().toISOString()}'
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('✅ Backup created successfully!\\\\n\\\\nLocation: ' + data.filepath + '\\\\nSize: ' + (data.size / 1024 / 1024).toFixed(2) + ' MB');
          } else {
            alert('❌ Backup failed: ' + data.error);
          }
        })
        .catch(error => {
          alert('💥 Backup failed: ' + error.message);
        });
      `);
    }
  });
}

function createDatabase() {
  const options = {
    type: 'question',
    title: 'Create Database',
    message: 'Create MySQL Database',
    detail: 'This will attempt to create the database if it doesn\'t exist. Continue?',
    buttons: ['Create', 'Cancel']
  };

  dialog.showMessageBox(mainWindow, options).then((result) => {
    if (result.response === 0) {
      mainWindow.webContents.executeJavaScript(`
        fetch('/api/desktop/create-database', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('✅ Database created successfully!');
          } else {
            alert('❌ Database creation failed: ' + data.error);
          }
        })
        .catch(error => {
          alert('💥 Database creation failed: ' + error.message);
        });
      `);
    }
  });
}

function runMigrations() {
  const options = {
    type: 'question',
    title: 'Run Database Migrations',
    message: 'Run Laravel Migrations',
    detail: 'This will run all pending database migrations. Continue?',
    buttons: ['Run Migrations', 'Cancel']
  };

  dialog.showMessageBox(mainWindow, options).then((result) => {
    if (result.response === 0) {
      mainWindow.webContents.executeJavaScript(`
        fetch('/api/desktop/run-migrations', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('✅ Migrations completed successfully!\\\\n\\\\nMigrations run: ' + data.migrations);
          } else {
            alert('❌ Migrations failed: ' + data.error);
          }
        })
        .catch(error => {
          alert('💥 Migrations failed: ' + error.message);
        });
      `);
    }
  });
}

function clearCache() {
  mainWindow.webContents.executeJavaScript(`
    fetch('/api/desktop/clear-cache', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('✅ Cache cleared successfully!');
        window.location.reload();
      } else {
        alert('❌ Cache clear failed: ' + data.error);
      }
    })
    .catch(error => {
      alert('💥 Cache clear failed: ' + error.message);
    });
  `);
}

function openEnvFile() {
  const envPath = path.join(__dirname, '../.env');
  shell.openPath(envPath).then(() => {
    console.log('Opened .env file');
  }).catch(err => {
    dialog.showErrorBox('Error', `Could not open .env file: ${err.message}`);
  });
}

function exportData() {
  const options = {
    title: 'Export Data',
    defaultPath: path.join(app.getPath('documents'), `laravel-export-${new Date().getTime()}.json`),
    filters: [
      { name: 'JSON Files', extensions: ['json'] },
      { name: 'CSV Files', extensions: ['csv'] },
      { name: 'All Files', extensions: ['*'] }
    ]
  };

  dialog.showSaveDialog(mainWindow, options).then((result) => {
    if (!result.canceled) {
      // Implement export logic here
      dialog.showMessageBox(mainWindow, {
        type: 'info',
        title: 'Export Data',
        message: 'Export functionality',
        detail: 'Data export would be saved to: ' + result.filePath
      });
    }
  });
}

function openSettings() {
  if (!mainWindow) return;

  mainWindow.webContents.executeJavaScript(`
    // Navigate to settings page or open settings modal
    if (typeof window.openSettings === 'function') {
      window.openSettings();
    } else {
      alert('Settings page not available in current view');
    }
  `);
}

function showAboutDialog() {
  dialog.showMessageBox(mainWindow, {
    type: 'info',
    icon: getIconPath(),
    title: 'About',
    message: 'Laravel Desktop App',
    detail: `Version: ${app.getVersion()}
Electron: ${process.versions.electron}
Node.js: ${process.versions.node}
Chromium: ${process.versions.chrome}

A Laravel application running in Electron with MySQL support.

© ${new Date().getFullYear()} Your Company Name`
  });
}

function checkDatabaseConnection() {
  // Initial database connection check
  setTimeout(() => {
    testDatabaseConnection();
  }, 2000);
}

// App event handlers
app.whenReady().then(() => {
  createWindow();

  // On macOS, re-create window when dock icon is clicked
  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow();
    }
  });
});

// Quit when all windows are closed
app.on('window-all-closed', () => {
  // On macOS, keep app running even when all windows are closed
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

// Security: Prevent new window creation
app.on('web-contents-created', (event, contents) => {
  contents.on('new-window', (event, navigationUrl) => {
    event.preventDefault();
    shell.openExternal(navigationUrl);
  });

  // Prevent navigation to external URLs
  contents.on('will-navigate', (event, navigationUrl) => {
    const parsedUrl = new URL(navigationUrl);
    const isLocal = parsedUrl.hostname === 'localhost' || 
                   parsedUrl.hostname === '127.0.0.1' ||
                   !parsedUrl.hostname;

    if (!isLocal) {
      event.preventDefault();
      shell.openExternal(navigationUrl);
    }
  });
});

// Handle app before quitting
app.on('before-quit', (event) => {
  // Add any cleanup logic here
  console.log('App is quitting...');
});

// IPC handlers for renderer process
ipcMain.handle('get-app-version', () => {
  return app.getVersion();
});

ipcMain.handle('get-app-path', () => {
  return app.getAppPath();
});

ipcMain.handle('get-user-data-path', () => {
  return app.getPath('userData');
});

ipcMain.handle('show-item-in-folder', (event, path) => {
  shell.showItemInFolder(path);
});

ipcMain.handle('open-external', (event, url) => {
  shell.openExternal(url);
});

ipcMain.handle('show-message-box', (event, options) => {
  return dialog.showMessageBox(mainWindow, options);
});

ipcMain.handle('show-save-dialog', (event, options) => {
  return dialog.showSaveDialog(mainWindow, options);
});

ipcMain.handle('show-open-dialog', (event, options) => {
  return dialog.showOpenDialog(mainWindow, options);
});

// Database operations via IPC
ipcMain.handle('database-operation', async (event, operation, data) => {
  try {
    switch (operation) {
      case 'test-connection':
        return await testDBConnection();
      case 'get-stats':
        return await getDatabaseStats();
      case 'backup':
        return await backupDB(data);
      default:
        return { error: 'Unknown operation' };
    }
  } catch (error) {
    return { error: error.message };
  }
});

// Global exception handler
process.on('uncaughtException', (error) => {
  console.error('Uncaught Exception:', error);
  dialog.showErrorBox('Unexpected Error', `
An unexpected error occurred:

${error.message}

The application may become unstable. Please restart the app.
  `);
});

// Graceful shutdown
app.on('before-quit', () => {
  console.log('Shutting down Laravel Desktop App...');
});