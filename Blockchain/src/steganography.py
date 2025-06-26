from flask import Flask, request, jsonify
import base64
import os
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS to allow frontend access

@app.route('/get_certificate', methods=['GET'])
def get_certificate():
    hash_value = request.args.get('hash')

    # Assuming images are stored in a folder named "certificates"
    image_path = f"certificates/{hash_value}.png"

    if not os.path.exists(image_path):
        return jsonify({"error": "Certificate image not found"}), 404

    try:
        with open(image_path, "rb") as image_file:
            encoded_string = base64.b64encode(image_file.read()).decode()
            return jsonify({"image": "data:image/png;base64," + encoded_string})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=5000, debug=True)
