ALTER TABLE companies ADD COLUMN user_id INT;
ALTER TABLE companies ADD CONSTRAINT fk_companies_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
