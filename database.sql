CREATE DATABASE IF NOT EXISTS w_c_issues;
USE w_c_issues;

-- Users Table (Admin and User roles)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Main Content Table (Multi-language storage fields)
CREATE TABLE IF NOT EXISTS site_content (
    content_key VARCHAR(50) PRIMARY KEY,
    content_en TEXT NOT NULL,
    content_am TEXT NOT NULL
);

-- News Table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_am VARCHAR(255) NOT NULL,
    body_en TEXT NOT NULL,
    body_am TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Audit Logs Table for Admin Actions
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    action VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Initial Data
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$g1k7n0X.WfB6uOnm.K1B1OnXgXU1K5B/S3p1lYkP01G/X1Admi123', 'admin'), -- Password: password123
('demo_user', '$2y$10$g1k7n0X.WfB6uOnm.K1B1OnXgXU1K5B/S3p1lYkP01G/X1Admi123', 'user');

INSERT INTO site_content (content_key, content_en, content_am) VALUES
('mission', 'To empower and protect the rights of women and children.', 'የሴቶችና ሕፃናትን መብትና ተጠቃሚነት ማረጋገጥ::'),
('vision', 'A society safe for every child and equal for every woman.', 'ለሴቶች እኩልነትና ለሕፃናት ደህንነት የተመቸች ማህበረሰብ መፍጠር::'),
('values', 'Equality, Safety, Empowerment, Compassion.', 'እኩልነት፣ ደህንነት፣ ማብቃት፣ ርህራሄ::'),
('goals', 'Reduce violence, promote healthcare access, support legal protection.', 'ጥቃትን መቀነስ፣ የጤና ተደራሽነትን ማሳደግ፣ ህጋዊ ከለላ መስጠት::'),
('services', 'Legal advisory, psychological support, community educational programs.', 'የሕግ ምክር፣ የስነ-ልቦና ድጋፍ፣ የማህበረሰብ ትምህርት ፕሮግራሞች::'),
('resources', 'Educational handbooks, emergency hotlines, shelter access locators.', 'የትምህርት መጽሃፍት፣ የአደጋ ጊዜ የስልክ መስመሮች፣ የጥገኝነት መጠለያዎች::'),
('history', 'Established to support vulnerability reduction initiatives.', 'ለችግር ተጋላጭ የሆኑ የሕብረተሰብ ክፍሎችን ለመደገፍ ተቋቋመ::');