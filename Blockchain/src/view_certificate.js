async function viewCertificate() {
    // Step 1: Fetch certificate hash from PHP MySQL
    const response = await fetch("get_certificate.php");
    const data = await response.json();

    if (data.error) {
        alert("No certificate found in the database.");
        return;
    }

    const certificateHash = data.hash;

    // Step 2: Verify hash in Ganache Blockchain
    const isValid = await certificateContract.methods.validateCertificate(certificateHash).call();

    if (!isValid) {
        alert("❌ Certificate is not valid on blockchain.");
        return;
    }

    // Step 3: Fetch certificate image from Flask Server
    fetch(`http://localhost:5000/get_certificate?hash=${certificateHash}`)
        .then(response => response.json())
        .then(data => {
            if (data.image) {
                document.getElementById("certificateImage").src = data.image;
            } else {
                alert("Certificate image not found!");
            }
        })
        .catch(error => console.error("Error fetching certificate:", error));
}