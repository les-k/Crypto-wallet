const fs = require("fs");
const crypto = require("crypto");
const path = require("path");
const externalDevice = require("secure-external-device");

const algorithm = "aes-256-cbc";
const password = "your_secure_password";

async function handleBackup(account, filePath) {
  try {
    // Encrypt the private key using the password
    const cipher = crypto.createCipher(algorithm, password);
    let encryptedPrivateKey = cipher.update(account.privateKey, "utf8", "hex");
    encryptedPrivateKey += cipher.final("hex");

    // Write the encrypted private key to a file on a secure external device
    await externalDevice.writeFile(filePath, encryptedPrivateKey);
    console.log(`Private key backup created: ${filePath}`);
  } catch (error) {
    console.error(`Error creating private key backup: ${error}`);
  }
}

async function handleRecovery(filePath) {
  try {
    // Read the encrypted private key from the file on the secure external device
    const encryptedPrivateKey = await externalDevice.readFile(filePath);
    if (!encryptedPrivateKey) {
      throw new Error("Private key not found in backup file");
    }

    // Decrypt the private key using the password
    const decipher = crypto.createDecipher(algorithm, password);
    let privateKey = decipher.update(encryptedPrivateKey, "hex", "utf8");
    privateKey += decipher.final("utf8");

    console.log(`Private key recovered: ${privateKey}`);
    return privateKey;
  } catch (error) {
    console.error(`Error recovering private key: ${error}`);
  }
}

// Example usage:
const account = {
  address: "0x0123456789abcdef0123456789abcdef01234567",
  privateKey: "0x0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01"
};
const filePath = path.join("/secure/external/device", "private-key-backup.txt");
handleBackup(account, filePath);
const recoveredPrivateKey = handleRecovery(filePath);



