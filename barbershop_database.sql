
CREATE DATABASE IF NOT EXISTS barbershop;
USE barbershop;

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    UNIQUE (appointment_date, appointment_time)
);
