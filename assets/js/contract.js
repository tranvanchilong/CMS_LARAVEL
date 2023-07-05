const initialize = async () => {
    let accounts;
    let networkValue;
    let chainIdValue;

    const onboardButton = document.getElementById('connectButton');
    const deposit_method = document.getElementById('deposit_method');
    const transferTokens = document.getElementById('transferTokens')
    const accountsDiv = document.getElementById('accounts')
    const accounts_Div = document.getElementById('accounts_div')
    const addMoneyDiv = document.getElementById('addMoney')
    const show_transaction = document.getElementById('show_transaction')
    const formDeposit = document.getElementById('formDeposit')
    const amountInput = document.getElementById('amountInput')
    const tokenInput = document.getElementById('tokenInput')
    const transactionInput = document.getElementById('transactionInput')
    const addressTo = document.getElementById('address_to')
    const rate = document.getElementById('rate')
    const contract_address = document.getElementById('contract_address')

    const isMetaMaskInstalled = () => {
        const { ethereum } = window;
        return Boolean(ethereum && ethereum.isMetaMask);
    };
    
    const isMetaMaskConnected = () => accounts && accounts.length > 0

    const onClickInstall = () => {
        onboardButton.innerText = 'After installing, please reload the page';
        onboardButton.disabled = true;
        window.open('https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn/related')
    };

    const onClickConnect = async () => {
        try {
            const newAccounts = await ethereum.request({
            method: 'eth_requestAccounts',
            })
            handleNewAccounts(newAccounts)
        } catch (error) {
            console.error(error);
        }
    };

    amountInput.addEventListener('keyup', () => {
        tokenInput.value = parseInt(amountInput.value * parseFloat(rate.value));
    })
    
    transferTokens.addEventListener('click', async () => {

        let value = amountInput.value;
        if(!value || value <= 0){
            return false;
        }        
        

        let shivaAmount = parseInt(value * parseFloat(rate.value));

        shivaAmount *= 100;

        const transactionParameters = {
            from: accounts[0],
            to: contract_address.value,
            data: getDataFieldValue(addressTo.value, shivaAmount),
        };
        
        let txt = await ethereum.request({
            method: 'eth_sendTransaction',
            params: [transactionParameters],
        });

        transactionInput.value = txt;
        formDeposit.submit();
        console.log(txt);
    });

    function handleNewAccounts (newAccounts) {
        if(newAccounts && newAccounts.length > 0){
            accounts = newAccounts
            accountsDiv.innerHTML = accounts;
            transferTokens.disabled = false;

            addMoneyDiv.classList.remove('hidden');
            accounts_Div.classList.remove('hidden');
            onboardButton.classList.add('hidden');
            deposit_method.classList.add('hidden');
            show_transaction.classList.remove('hidden');
        }
    }

    function getDataFieldValue(tokenRecipientAddress, tokenAmount) {
        const web3 = new Web3();
        const TRANSFER_FUNCTION_ABI = {"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"};
        return web3.eth.abi.encodeFunctionCall(TRANSFER_FUNCTION_ABI, [
            tokenRecipientAddress,
            tokenAmount
        ]);
    }
    
    async function getNetworkAndChainId () {
        try {
          const chainId = await ethereum.request({
            method: 'eth_chainId',
          })
          handleNewChain(chainId)
    
          const networkId = await ethereum.request({
            method: 'net_version',
          })
          handleNewNetwork(networkId)
        } catch (err) {
          console.error(err)
        }
    }
    
    function handleNewChain (chainId) {
        console.log(chainId);
        chainIdValue = chainId;
    }

    function handleNewNetwork (networkId) {
        networkValue = networkId;
        console.log(networkId);

    }


    if (!isMetaMaskInstalled()) {
        onboardButton.innerText = 'Click here to install MetaMask!';
        onboardButton.onclick = onClickInstall;
        onboardButton.disabled = false;
    } else {
        onboardButton.innerText = 'Connect to MetaMask';
        onboardButton.onclick = onClickConnect;
        onboardButton.disabled = false;
        
        getNetworkAndChainId()
        ethereum.on('chainChanged', handleNewChain)
        ethereum.on('networkChanged', handleNewNetwork)
        ethereum.on('accountsChanged', handleNewAccounts)
    
        try {
            const newAccounts = await ethereum.request({
                method: 'eth_accounts',
            })
            handleNewAccounts(newAccounts)
        } catch (err) {
            console.error('Error on init when getting accounts', err)
        }
    }
};

window.addEventListener('DOMContentLoaded', initialize);
