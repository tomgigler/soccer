from flask import Flask, request
import subprocess

app = Flask(__name__)

@app.route("/update-webhook", methods=["POST"])
def webhook():
    """Trigger deploy.sh on a push event"""
    subprocess.run(["/var/www/html/soccer/deploy.sh"])
    return "Deployment triggered", 200

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
