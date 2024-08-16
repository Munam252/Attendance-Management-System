-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: attendance
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Attendance`
--

DROP TABLE IF EXISTS `Attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Attendance` (
  `attendance_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `Attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Attendance`
--

LOCK TABLES `Attendance` WRITE;
/*!40000 ALTER TABLE `Attendance` DISABLE KEYS */;
INSERT INTO `Attendance` VALUES (1,1,'2024-08-13','Present'),(2,2,'2024-08-13','Absent'),(3,1,'2024-08-15','Present'),(4,4,'2024-08-15','Present');
/*!40000 ALTER TABLE `Attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LeaveRequest`
--

DROP TABLE IF EXISTS `LeaveRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `LeaveRequest` (
  `leave_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `leave_date` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`leave_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `LeaveRequest_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LeaveRequest`
--

LOCK TABLES `LeaveRequest` WRITE;
/*!40000 ALTER TABLE `LeaveRequest` DISABLE KEYS */;
INSERT INTO `LeaveRequest` VALUES (1,1,'2024-08-16','I am sick','rejected'),(2,1,'2024-08-17','Temperature','approved'),(3,2,'2024-08-15','ill','Pending'),(4,4,'2024-08-30','IDk\r\n','Pending');
/*!40000 ALTER TABLE `LeaveRequest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LoginRecord`
--

DROP TABLE IF EXISTS `LoginRecord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `LoginRecord` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `login_date` date NOT NULL,
  `login_time` time NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `LoginRecord_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LoginRecord`
--

LOCK TABLES `LoginRecord` WRITE;
/*!40000 ALTER TABLE `LoginRecord` DISABLE KEYS */;
INSERT INTO `LoginRecord` VALUES (1,1,'2024-08-15','11:12:22'),(2,1,'2024-08-15','11:13:06'),(3,2,'2024-08-15','11:13:22'),(4,1,'2024-08-15','11:48:56'),(5,1,'2024-08-15','11:50:13'),(6,1,'2024-08-15','12:22:19'),(7,4,'2024-08-15','16:55:56');
/*!40000 ALTER TABLE `LoginRecord` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Admin253','Admin253@');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `roll_no` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,'21i-0460','Munam253','Munam253@','Munam Mustafa','munammustafa253@gmail.com'),(2,'21i-0456','Ammar253','Ammar253@','Ammar Arshad','ammararshad253@gmail.com'),(3,'21i-0511','Raza253','Raza253@','Muhammad Raza','muhammadraza253@gmail.com'),(4,'21i-0631','Muzammil253@','Muzammil253@','Muzammil Hassan','muzammilhassan253@gmail.com');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-16 11:53:36
