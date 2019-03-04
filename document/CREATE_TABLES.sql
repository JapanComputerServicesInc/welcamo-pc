-- ********************************************************
-- DB作成用
-- ********************************************************
CREATE DATABASE IF NOT EXISTS welcamo DEFAULT CHARACTER SET utf8;
CREATE USER 'welcamo'@'%' IDENTIFIED BY 'U95z8uBMgHMZ';
GRANT ALL PRIVILEGES ON welcamo.* TO 'welcamo'@'%';

-- :: 開発用 ::
-- CREATE DATABASE IF NOT EXISTS welcamo_dev DEFAULT CHARACTER SET utf8;
-- CREATE USER 'welcamo_dev'@'%' IDENTIFIED BY 'YGftGXsR';
-- GRANT ALL PRIVILEGES ON welcamo_dev.* TO 'welcamo_dev'@'%';


-- ********************************************************
-- マスタ
-- ********************************************************

-- --------------------------
-- 名称マスタ
-- --------------------------
DROP TABLE IF EXISTS names;
CREATE TABLE IF NOT EXISTS names
(
    id                       INT                       NOT NULL AUTO_INCREMENT COMMENT '名称ID',
    key_cd                   VARCHAR(20)               NOT NULL COMMENT '分類キーCD',
    nm_key_cd                VARCHAR(20)               NOT NULL COMMENT '名称キーCD',
    kbn_val                  VARCHAR(20)               NOT NULL COMMENT '区分値',
    kbn_nm1                  VARCHAR(20)               NOT NULL COMMENT '区分名称1',
    kbn_nm2                  VARCHAR(40)                        COMMENT '区分名称2',
    remarks                  VARCHAR(40)                        COMMENT '備考',
    sort_no                  INT                       NOT NULL COMMENT 'ソート順',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    CONSTRAINT PRIMARY KEY (id),
    UNIQUE INDEX UX_NAMES_001 (key_cd, nm_key_cd, kbn_val)
) ENGINE INNODB;

-- --------------------------
-- ユーザーマスタ
-- --------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users
(
    id                       INT                       NOT NULL AUTO_INCREMENT COMMENT 'ユーザーID',
    email                    VARCHAR(254)                       COMMENT 'E-Mailアドレス',
    user_name                VARCHAR(20)               NOT NULL COMMENT 'ユーザー名',
    password                 VARCHAR(255)              NOT NULL COMMENT 'パスワード',
    remember_token           VARCHAR(100)                       COMMENT 'リメンバートークン',
    role                     CHAR(1)                   NOT NULL COMMENT '役割',
    short_name               VARCHAR(10)               NOT NULL COMMENT '略称',
    reception                CHAR(1)                   NOT NULL COMMENT '受付',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    deleted_at               DATETIME                           COMMENT '削除日時',
    CONSTRAINT PRIMARY KEY (id),
    INDEX IX_USERS_001 (email)
) ENGINE INNODB;

-- --------------------------
-- 入館理由マスタ
-- --------------------------
DROP TABLE IF EXISTS purposes;
CREATE TABLE IF NOT EXISTS purposes
(
    id                       INT                       NOT NULL AUTO_INCREMENT COMMENT '入館理由ID',
    purpose                  VARCHAR(40)               NOT NULL COMMENT '入館理由',
    sort_no                  INT                       NOT NULL COMMENT 'ソート順',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    deleted_at               DATETIME                           COMMENT '削除日時',
    CONSTRAINT PRIMARY KEY (id)
) ENGINE INNODB;

-- --------------------------
-- 入館証マスタ
-- --------------------------
DROP TABLE IF EXISTS admissions;
CREATE TABLE IF NOT EXISTS admissions
(
    id                       INT                       NOT NULL AUTO_INCREMENT COMMENT '入館証ID',
    no                       VARCHAR(12)                        COMMENT '入館証NO',
    display_no               VARCHAR(12)               NOT NULL COMMENT '表示名',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    deleted_at               DATETIME                           COMMENT '削除日時',
    CONSTRAINT PRIMARY KEY (id),
    INDEX IX_ADMISSIONS_001 (no)
) ENGINE INNODB;


-- ********************************************************
-- トランザクション
-- ********************************************************

-- --------------------------
-- 入館予定
-- --------------------------
DROP TABLE IF EXISTS schedules;
CREATE TABLE IF NOT EXISTS schedules
(
    id                       BIGINT                    NOT NULL AUTO_INCREMENT COMMENT '予定ID',
    schedule_date            DATE                      NOT NULL COMMENT '入館予定日',
    company_name             VARCHAR(60)               NOT NULL COMMENT '会社名（代表）',
    visitor_name             VARCHAR(20)               NOT NULL COMMENT '入館者名（代表）',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    CONSTRAINT PRIMARY KEY (id),
    INDEX IX_SCHECULES_001 (schedule_date)
) ENGINE INNODB;

-- --------------------------
-- 入退館履歴
-- --------------------------
DROP TABLE IF EXISTS histories;
CREATE TABLE IF NOT EXISTS histories
(
    id                       BIGINT                    NOT NULL AUTO_INCREMENT COMMENT '履歴ID',
    visit_area               VARCHAR(20)               NOT NULL COMMENT '入館エリア',
    visit_dt                 DATETIME                  NOT NULL COMMENT '入館日時',
    company_name             VARCHAR(60)               NOT NULL COMMENT '会社名（代表）',
    visitor_name             VARCHAR(20)               NOT NULL COMMENT '入館者名（代表）',
    reception_user_id        INT                       NOT NULL COMMENT '受付者ID',
    purpose_id               INT                       NOT NULL COMMENT '入館理由ID',
    purpose_remarks          VARCHAR(400)                       COMMENT '入館理由（補足）',
    last_dt                  DATETIME                           COMMENT '最終退館日時',
    approval_user_id         INT                                COMMENT '確認責任者ID',
    approval_dt              DATETIME                           COMMENT '確認日時',
    schedule_id              BIGINT                    NOT NULL COMMENT '入館予定ID',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    CONSTRAINT PRIMARY KEY (id),
    INDEX IX_HISTORIES_001 (visit_dt),
    INDEX IX_HISTORIES_002 (last_dt),
    INDEX IX_HISTORIES_003 (approval_dt),
    INDEX IX_HISTORIES_004 (schedule_id)
) ENGINE INNODB;

-- --------------------------
-- 入退館者
-- --------------------------
DROP TABLE IF EXISTS visitors;
CREATE TABLE IF NOT EXISTS visitors
(
    id                       BIGINT                    NOT NULL AUTO_INCREMENT COMMENT '入退館者ID',
    history_id               BIGINT                    NOT NULL COMMENT '入退館履歴ID',
    admission_id             INT                       NOT NULL COMMENT '入館証ID',
    signature                MEDIUMBLOB                NOT NULL COMMENT '入館者サイン',
    exit_dt                  DATETIME                           COMMENT '退館日時',
    created_at               DATETIME                  NOT NULL COMMENT '登録日時',
    created_by               INT                       NOT NULL COMMENT '登録者',
    updated_at               DATETIME                           COMMENT '最終更新日時',
    updated_by               INT                                COMMENT '最終更新者',
    CONSTRAINT PRIMARY KEY (id),
    UNIQUE INDEX UX_VISITORS_001 (history_id, admission_id),
    INDEX IX_VISITORS_001 (exit_dt)
) ENGINE INNODB;


-- ********************************************************
-- データ
-- ********************************************************
-- :: names ::
INSERT INTO names (key_cd,nm_key_cd,kbn_val,kbn_nm1,kbn_nm2,remarks,sort_no,created_at,created_by) VALUES ('SYSTEM','ROLE','1','責任者',NULL,NULL,1,NOW(),1);
INSERT INTO names (key_cd,nm_key_cd,kbn_val,kbn_nm1,kbn_nm2,remarks,sort_no,created_at,created_by) VALUES ('SYSTEM','ROLE','2','担当者',NULL,NULL,2,NOW(),1);
INSERT INTO names (key_cd,nm_key_cd,kbn_val,kbn_nm1,kbn_nm2,remarks,sort_no,created_at,created_by) VALUES ('SYSTEM','RECEPTION','0','不可',NULL,NULL,1,NOW(),1);
INSERT INTO names (key_cd,nm_key_cd,kbn_val,kbn_nm1,kbn_nm2,remarks,sort_no,created_at,created_by) VALUES ('SYSTEM','RECEPTION','1','可能',NULL,NULL,2,NOW(),1);
INSERT INTO names (key_cd,nm_key_cd,kbn_val,kbn_nm1,kbn_nm2,remarks,sort_no,created_at,created_by) VALUES ('SYSTEM','ENTRY_AREA','1','LCMセンター',NULL,NULL,1,NOW(),1);

-- :: users ::
INSERT INTO users (id, email, user_name, password, role, short_name, reception, created_at, created_by) VALUES (1,'welcamo@japacom.co.jp','WELCAMO　管理者','$2y$12$lW0XcnJa4PD9KId/XuaMBeo4vHe4OeyubFshOFtIDUTNHNmqYg7d2','1','管理者','1',NOW(),1);

-- :: purposes ::
INSERT INTO purposes (id,purpose,sort_no,created_at,created_by) VALUES (1,'打合せ',1,NOW(),1);
INSERT INTO purposes (id,purpose,sort_no,created_at,created_by) VALUES (2,'作業',2,NOW(),1);
INSERT INTO purposes (id,purpose,sort_no,created_at,created_by) VALUES (3,'納品',3,NOW(),1);
INSERT INTO purposes (id,purpose,sort_no,created_at,created_by) VALUES (4,'視察（見学）',4,NOW(),1);
INSERT INTO purposes (id,purpose,sort_no,created_at,created_by) VALUES (5,'その他',99,NOW(),1);

-- :: admissions ::
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (1 ,'LCM-001','LCM-001',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (2 ,'LCM-002','LCM-002',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (3 ,'LCM-003','LCM-003',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (4 ,'LCM-004','LCM-004',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (5 ,'LCM-005','LCM-005',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (6 ,'LCM-006','LCM-006',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (7 ,'LCM-007','LCM-007',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (8 ,'LCM-008','LCM-008',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (9 ,'LCM-009','LCM-009',NOW(),1);
INSERT INTO admissions (id,no,display_no,created_at,created_by) VALUES (10,'LCM-010','LCM-010',NOW(),1);

