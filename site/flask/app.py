from flask import Flask, request, render_template, redirect, url_for, send_from_directory
from flask_sqlalchemy import SQLAlchemy
from werkzeug.utils import secure_filename
from dotenv import load_dotenv
import os
import subprocess
import requests

# .env 파일에서 환경 변수 불러오기
load_dotenv()

app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = '/app/uploads'
app.config['SQLALCHEMY_DATABASE_URI'] = (
    f"postgresql://{os.getenv('POSTGRES_USER')}:{os.getenv('POSTGRES_PASSWORD')}"
    f"@{os.getenv('POSTGRES_HOST')}:{os.getenv('POSTGRES_PORT')}/{os.getenv('POSTGRES_DB')}"
)
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# -------------------- Stored XSS용 모델 --------------------
class Comment(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    content = db.Column(db.Text, nullable=False)

# -------------------- User 테이블 (SQLi용) --------------------
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), nullable=False)
    password = db.Column(db.String(64), nullable=False)

# -------------------- 메인 페이지 --------------------
@app.route('/')
def index():
    return render_template('index.html')

# -------------------- 1. Reflected XSS --------------------
@app.route('/search')
def search():
    query = request.args.get('q', '')
    return render_template('search.html', query=query)

# -------------------- 2. Stored XSS --------------------
@app.route('/comment', methods=['GET', 'POST'])
def comment():
    if request.method == 'POST':
        content = request.form['content']
        db.session.add(Comment(content=content))
        db.session.commit()
        return redirect(url_for('comment'))
    comments = Comment.query.all()
    return render_template('comment.html', comments=comments)

# -------------------- 3. DOM XSS --------------------
@app.route('/dom-xss')
def dom_xss():
    return render_template('dom_xss.html')

# -------------------- 4. SQL Injection (Error-based) --------------------
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        try:
            connection = db.engine.raw_connection()
            cursor = connection.cursor()
            query = f"SELECT * FROM user WHERE username = '{username}' AND password = '{password}'"
            cursor.execute(query)
            result = cursor.fetchone()
            connection.close()

            if result:
                return "로그인 성공!"
            else:
                return "아이디 또는 비밀번호가 틀렸습니다."
        except Exception as e:
            return str(e)
    return render_template('login.html')

# -------------------- 5. File Upload --------------------
@app.route('/upload', methods=['GET', 'POST'])
def upload():
    message = ''
    if request.method == 'POST':
        if 'file' not in request.files:
            message = '파일이 없습니다.'
        else:
            file = request.files['file']
            if file.filename == '':
                message = '파일명을 입력해주세요.'
            else:
                filename = secure_filename(file.filename)  # 필터링 부족한 상태
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                message = f'{filename} 업로드 완료'
    return render_template('upload.html', message=message)

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

# -------------------- 6. Command Injection --------------------
@app.route('/ping', methods=['GET', 'POST'])
def ping():
    output = ''
    if request.method == 'POST':
        host = request.form.get('host', '').strip()
        try:
            # 호스트 검증 로직 제거, 직접 ping 호출
            result = subprocess.run(
                ['ping', '-n', '2', host],
                capture_output=True,
                text=True
            )
            output = result.stdout or result.stderr
        except Exception as e:
            output = str(e)
    return render_template('ping.html', output=output)


# -------------------- 7. SSRF --------------------
@app.route('/fetch', methods=['GET', 'POST'])
def fetch():
    response_text = ''
    if request.method == 'POST':
        url = request.form['url']
        if not url.startswith(('http://', 'https://')):
            url = 'http://' + url  # 기본적으로 http 프로토콜 사용
        try:
            print(f"Fetching URL: {url}", flush=True)  # 디버깅용 로그
            app.logger.info(f"Fetching URL: {url}")
            r = requests.get(url, timeout=10)
            response_text = r.text[:300]
        except Exception as e:
            response_text = str(e)
    return render_template('fetch.html', response=response_text)

# -------------------- 8. File Download --------------------
# 다운로드 UI 페이지
@app.route('/download')
def download():
    filename = request.args.get('file')
    if not filename:
        # 파일명이 없으면 다운로드 페이지 렌더링
        return render_template('download.html')

    # 파일명이 있으면 다운로드 로직 수행
    download_dir = os.path.abspath(app.config['UPLOAD_FOLDER'])
    requested_path = os.path.abspath(os.path.join(download_dir, filename.lstrip('/')))

    if not os.path.isfile(requested_path):
        return "파일이 존재하지 않습니다.", 404

    return send_from_directory(
        directory=os.path.dirname(requested_path),
        path=os.path.basename(requested_path),
        as_attachment=True
    )

# -------------------- 서버 실행 --------------------
if __name__ == '__main__':
    os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)
    with app.app_context():
        db.create_all()
    app.run(host='0.0.0.0', port=8082, debug=True)