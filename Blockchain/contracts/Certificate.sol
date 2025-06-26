// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

contract Certificate {
    struct Cert {
        string studentName;
        string courseName;
        string issueDate;
        bool isValid;
    }

    mapping(string => Cert) private certificates; // Made private for security
    address public admin;

    event CertificateIssued(string certId, string studentName, string courseName);
    event CertificateRevoked(string certId);

    constructor() {
        admin = msg.sender;
    }

    function issueCertificate(
        string memory certId,
        string memory studentName,
        string memory courseName,
        string memory issueDate
    ) public {
        require(msg.sender == admin, "Only admin can issue certificates");
        require(bytes(certId).length > 0, "Certificate ID cannot be empty");
        require(bytes(certificates[certId].studentName).length == 0, "Certificate already exists");

        certificates[certId] = Cert(studentName, courseName, issueDate, true);
        emit CertificateIssued(certId, studentName, courseName);
    }

    function validateCertificate(string memory certId) public view returns (bool) {
        require(bytes(certId).length > 0, "Invalid certificate ID");
        Cert memory cert = certificates[certId];
        
        // Check if the certificate exists
        if (bytes(cert.studentName).length == 0) {
            return false; // Certificate does not exist
        }

        return cert.isValid;
    }

    function revokeCertificate(string memory certId) public {
        require(msg.sender == admin, "Only admin can revoke certificates");
        require(bytes(certId).length > 0, "Invalid certificate ID");
        require(certificates[certId].isValid, "Certificate is already revoked");

        certificates[certId].isValid = false;
        emit CertificateRevoked(certId);
    }

    function getCertificate(string memory certId) public view returns (
        string memory studentName,
        string memory courseName,
        string memory issueDate,
        bool isValid
    ) {
        require(bytes(certId).length > 0, "Invalid certificate ID");
        Cert memory cert = certificates[certId];

        // Ensure certificate exists before returning data
        require(bytes(cert.studentName).length > 0, "Certificate not found");

        return (cert.studentName, cert.courseName, cert.issueDate, cert.isValid);
    }
}

