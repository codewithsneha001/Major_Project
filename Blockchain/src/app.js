const contractAddress = "0xf8Ed9BD8c9CC5c7A91007943A10358D5504f9657"; // Replace with your contract address
const contractABI = [
    {
        "inputs": [],
        "stateMutability": "nonpayable",
        "type": "constructor"
      },
      {
        "anonymous": false,
        "inputs": [
          {
            "indexed": false,
            "internalType": "string",
            "name": "certId",
            "type": "string"
          },
          {
            "indexed": false,
            "internalType": "string",
            "name": "studentName",
            "type": "string"
          },
          {
            "indexed": false,
            "internalType": "string",
            "name": "courseName",
            "type": "string"
          }
        ],
        "name": "CertificateIssued",
        "type": "event"
      },
      {
        "anonymous": false,
        "inputs": [
          {
            "indexed": false,
            "internalType": "string",
            "name": "certId",
            "type": "string"
          }
        ],
        "name": "CertificateRevoked",
        "type": "event"
      },
      {
        "inputs": [],
        "name": "admin",
        "outputs": [
          {
            "internalType": "address",
            "name": "",
            "type": "address"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "string",
            "name": "certId",
            "type": "string"
          },
          {
            "internalType": "string",
            "name": "studentName",
            "type": "string"
          },
          {
            "internalType": "string",
            "name": "courseName",
            "type": "string"
          },
          {
            "internalType": "string",
            "name": "issueDate",
            "type": "string"
          }
        ],
        "name": "issueCertificate",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "inputs": [
          {
            "internalType": "string",
            "name": "certId",
            "type": "string"
          }
        ],
        "name": "validateCertificate",
        "outputs": [
          {
            "internalType": "bool",
            "name": "",
            "type": "bool"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      },
      {
        "inputs": [
          {
            "internalType": "string",
            "name": "certId",
            "type": "string"
          }
        ],
        "name": "revokeCertificate",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "inputs": [
          {
            "internalType": "string",
            "name": "certId",
            "type": "string"
          }
        ],
        "name": "getCertificate",
        "outputs": [
          {
            "internalType": "string",
            "name": "studentName",
            "type": "string"
          },
          {
            "internalType": "string",
            "name": "courseName",
            "type": "string"
          },
          {
            "internalType": "string",
            "name": "issueDate",
            "type": "string"
          },
          {
            "internalType": "bool",
            "name": "isValid",
            "type": "bool"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      }
];


var web3;
var contract;

async function initWeb3() {
    if (typeof window.ethereum !== "undefined") {
        web3 = new Web3(window.ethereum);
        await window.ethereum.request({ method: "eth_requestAccounts" }); // Request MetaMask access

        contract = new web3.eth.Contract(contractABI, contractAddress);
        console.log("✅ Web3 Initialized:", web3);
        console.log("✅ Contract Connected:", contract);

        // Listen for CertificateIssued event
        contract.events.CertificateIssued()
            .on('data', (event) => {
                console.log("✅ CertificateIssued event received:", event);
            })
            .on('error', console.error);
    } else {
        alert("❌ Please install MetaMask to use this feature!");
    }
}

async function issueCertificate() {
    if (!contract) {
        alert("❌ Contract is not initialized! Please refresh the page.");
        return;
    }

    const certId = document.getElementById("certId").value.trim();
    const studentName = document.getElementById("studentName").value.trim();
    const courseName = document.getElementById("courseName").value.trim();
    const issueDate = document.getElementById("issueDate").value;

    try {
        const accounts = await web3.eth.getAccounts();
        console.log("🔹 Issuing Certificate from account:", accounts[0]);

        await contract.methods.issueCertificate(certId, studentName, courseName, issueDate)
            .send({ from: accounts[0], gas: 2000000 }); // Adjust gas as needed


        alert("✅ Certificate Issued Successfully!");
        // window.location.href = "view_certificate.html";
    } catch (error) {
        console.error("❌ Error issuing certificate:", error);
        alert("Failed to create certificate: " + error.message);
    }
}

async function validateCertificate(certId) {
    if (!contract) {
        alert("❌ Contract is not initialized! Please refresh the page.");
        console.error("Contract is not initialized.");
        return;
    }

    console.log("Validating Certificate ID:", certId);

    try {
        const result = await contract.methods.validateCertificate(certId).call();
        console.log("Blockchain validation result:", result);

        if (result) {
            document.getElementById("validationResult").innerHTML = "✅ Certificate is valid!";
        } else {
            document.getElementById("validationResult").innerHTML = "❌ Certificate is not valid!";
        }
    } catch (error) {
        console.error("❌ Error validating certificate:", error);
        alert("Failed to validate certificate: " + error.message);
    }
}




// Revoke Certificate
async function revokeCertificate(certId) {
    if (!contract) {
        alert("❌ Contract is not initialized! Please refresh the page.");
        return;
    }

    try {
        const accounts = await web3.eth.getAccounts();
        console.log("🔹 Revoking Certificate from account:", accounts[0]);

        await contract.methods.revokeCertificate(certId)
            .send({ from: accounts[0] });

        document.getElementById("revokeResult").innerHTML = "✅ Certificate revoked successfully!";
    } catch (error) {
        console.error("❌ Error revoking certificate:", error);
        alert("Failed to revoke certificate: " + error.message);
    }
}


// Initialize Web3 when the page loads
window.addEventListener("load", initWeb3);