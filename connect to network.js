const Web3 = require("web3");
const web3 = new Web3("<network URL>");

async function connectToBlockchain() {
  try {
    // Check if web3 has been successfully injected by a provider (such as Metamask)
    if (web3.currentProvider) {
      console.log("Connected to blockchain network through injected provider.");
    } else {
      console.log("Connecting to blockchain network through remote node...");
      // Connect to remote blockchain node
      const provider = new Web3.providers.HttpProvider("<network URL>");
      web3.setProvider(provider);
    }
    // Check if the connection is successful
    const networkId = await web3.eth.net.getId();
    console.log(`Connected to network with id: ${networkId}`);
  } catch (error) {
    console.error(`Error connecting to blockchain network: ${error}`);
  }
}

connectToBlockchain();


