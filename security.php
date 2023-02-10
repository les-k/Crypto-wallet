const crypto = require('crypto');
const algorithm = 'aes-256-cbc';
const password = 'password_to_use_as_key';
const salt = crypto.randomBytes(16).toString('hex');
const encryptionPassword = 'encryption_password';

// Function to derive a key from the password
function deriveKey(password, salt) {
  return crypto.pbkdf2Sync(password, salt, 100000, 32, 'sha256').toString('hex');
}

// Function to encrypt the data
function encrypt(data) {
  const key = deriveKey(password, salt);
  const iv = crypto.randomBytes(16);
  const cipher = crypto.createCipheriv(algorithm, key, iv);
  let encrypted = cipher.update(data, 'utf8', 'hex');
  encrypted += cipher.final('hex');
  return { encryptedData: encrypted, iv: iv.toString('hex') };
}

// Function to decrypt the data
function decrypt(encryptedData, iv) {
  const key = deriveKey(password, salt);
  const decipher = crypto.createDecipheriv(algorithm, key, Buffer.from(iv, 'hex'));
  let decrypted = decipher.update(encryptedData, 'hex', 'utf8');
  decrypted += decipher.final('utf8');
  return decrypted;
}

// Function to encrypt the encryption password
function encryptPassword(password) {
  const key = crypto.randomBytes(32);
  const iv = crypto.randomBytes(16);
  const cipher = crypto.createCipheriv('aes-256-cbc', key, iv);
  let encrypted = cipher.update(password, 'utf8', 'hex');
  encrypted += cipher.final('hex');
  return { encryptedPassword: encrypted, key: key.toString('hex'), iv: iv.toString('hex') };
}

// Function to decrypt the encryption password
function decryptPassword(encryptedPassword, key, iv) {
  const decipher = crypto.createDecipheriv('aes-256-cbc', Buffer.from(key, 'hex'), Buffer.from(iv, 'hex'));
  let decrypted = decipher.update(encryptedPassword, 'hex', 'utf8');
  decrypted += decipher.final('utf8');
  return decrypted;
}

// Example usage
const privateKey = 'secret_private_key';
const encryptedPrivateKey = encrypt(privateKey);
console.log('Encrypted private key:', encryptedPrivateKey);

const encryptedPassword = encryptPassword(encryptionPassword);
console.log('Encrypted encryption password:', encryptedPassword);

const decryptedPassword = decryptPassword(encryptedPassword.encryptedPassword, encryptedPassword.key, encryptedPassword.iv);
console.log('Decrypted encryption password:', decryptedPassword);

const decryptedPrivateKey = decrypt(encryptedPrivateKey.encryptedData, encryptedPrivateKey.iv);
console.log('Decrypted private key:', decryptedPrivateKey);
