# TEST-SITE

웹 애플리케이션 보안 취약점 테스트를 위한 Docker 환경입니다.

## 🚀 프로젝트 구성

- **Flask 애플리케이션** (Python): PostgreSQL 연동
- **PHP 애플리케이션**: MySQL 연동  
- **MySQL 8**: PHP 애플리케이션용 데이터베이스
- **PostgreSQL 15**: Flask 애플리케이션용 데이터베이스
- **phpMyAdmin**: MySQL 관리 도구

## 📋 서비스 포트

| 서비스 | 포트 | 설명 |
|--------|------|------|
| Flask | 8082 | Python Flask 웹 애플리케이션 |
| PHP | 8081 | PHP 웹 애플리케이션 |
| phpMyAdmin | 8083 | MySQL 관리 도구 |
| MySQL | 3306 | MySQL 데이터베이스 |
| PostgreSQL | 5433 | PostgreSQL 데이터베이스 |

## 🛠️ 실행 방법

### 1. 서비스 실행

```bash
# 캐시 문제 없이 모든 서비스를 백그라운드에서 실행 (권장)
docker-compose up -d --build

# 또는 일반 실행
docker-compose up -d

# 실행 중인 서비스 확인
docker-compose ps

# 로그 확인
docker-compose logs [서비스명]
```

### 2. 서비스 중지

```bash
# 모든 서비스 중지 및 제거
docker-compose down

# 서비스 재시작
docker-compose restart
```

## 🔗 접속 정보

### Flask 애플리케이션
- **URL**: http://localhost:8082
- **데이터베이스**: PostgreSQL (포트 5433)
- **사용자**: vuln / vuln
- **데이터베이스**: vuln_db

### PHP 애플리케이션  
- **URL**: http://localhost:8081
- **데이터베이스**: MySQL (포트 3306)
- **사용자**: vuln / vuln

### 데이터베이스 관리
- **phpMyAdmin**: http://localhost:8083
- **MySQL 접속**: root / root

## 📊 Flask 애플리케이션 기능

### 보안 취약점 테스트 페이지

1. **Reflected XSS**: `/search` - 검색 결과에 스크립트 삽입
2. **Stored XSS**: `/comment` - 댓글에 스크립트 저장
3. **DOM XSS**: `/dom-xss` - 클라이언트 사이드 스크립트 실행
4. **SQL Injection**: `/login` - 로그인 폼을 통한 SQLi
5. **File Upload**: `/upload` - 파일 업로드 취약점
6. **Command Injection**: `/ping` - 시스템 명령어 실행
7. **SSRF**: `/fetch` - 서버 사이드 요청 위조
8. **File Download**: `/download` - 경로 순회 취약점

### 테스트 데이터

**사용자 계정 (SQLi 테스트용)**:
- admin / admin123
- test / test123  
- user1 / password1
- user2 / password2

## 🔧 데이터베이스 연결

Flask 애플리케이션은 시작 시 자동으로 다음을 수행합니다:

1. **PostgreSQL 연결 확인**
2. **테이블 자동 생성** (`user`, `comment`)

### 수동 데이터베이스 작업

```bash
# PostgreSQL 접속
docker exec -it postgres psql -U vuln -d vuln_db

# 테이블 확인
docker exec postgres psql -U vuln -d vuln_db -c "\dt"

# 데이터 확인
docker exec postgres psql -U vuln -d vuln_db -c "SELECT * FROM \"user\";"
```

## 🐛 문제 해결

### Flask 연결 오류 발생 시

```bash
# 서비스 재시작 (자동 재시도 정책 활용)
docker-compose restart

# 로그 확인
docker-compose logs flask
docker-compose logs postgres
```

### 캐시 문제 해결

```bash
# 컨테이너 재시작 및 이미지 재빌드
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## 📁 프로젝트 구조

```
TEST-SITE/
├── docker-compose.yml          # Docker Compose 설정
├── site/
│   ├── flask/                  # Flask 애플리케이션
│   │   ├── app.py             # 메인 애플리케이션
│   │   ├── Dockerfile         # Flask 컨테이너 설정
│   │   ├── requirements.txt   # Python 패키지
│   │   └── templates/         # HTML 템플릿
│   └── php/                   # PHP 애플리케이션
├── init/
│   ├── postgres/              # PostgreSQL 초기화 스크립트
│   └── mysql/                 # MySQL 초기화 스크립트
└── test/                      # 테스트 케이스 (YAML)
```

## ⚠️ 주의사항

- 이 프로젝트는 **보안 취약점 학습 목적**으로 제작되었습니다
- **실제 운영 환경에서 사용하지 마세요**
- 모든 취약점은 의도적으로 구현된 것입니다
