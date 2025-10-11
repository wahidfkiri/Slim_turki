const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('electronAPI', {
  getAppVersion: () => ipcRenderer.invoke('get-app-version'),
  showInFolder: (path) => ipcRenderer.invoke('show-in-folder', path),
  
  // Database operations
  testDatabase: () => ipcRenderer.invoke('database-operation', 'test-connection'),
  getDatabaseStats: () => ipcRenderer.invoke('database-operation', 'get-stats'),
  
  // File operations
  selectFolder: () => ipcRenderer.invoke('select-folder'),
  
  platform: process.platform,
  isElectron: true
});