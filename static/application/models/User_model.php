<?php
class User_model extends CI_Model {

    // Log in
    public function login($username, $password) {
        $sql = "SELECT * FROM Users U WHERE U.username='$username'AND U.password='$password'";
        $query = $this->db->query($sql);
        
        if($query->num_rows() == 1){
            return $query->result()[0];
        } else {
            return false;
        }
    }

    public function getUsername($userId) {
        $sql = "SELECT username FROM Users WHERE id = '$userId'";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getUserInfoById($userId) {
        $sql = "SELECT * FROM Users WHERE id = '$userId'";
        $query = $this->db->query($sql);

        if($query->num_rows() == 1){
            return $query->result()[0];
        } else {
            return false;
        }
    }

    public function register($username, $password, $email, $userType) {
        $sqlFirst = "SELECT * FROM Users WHERE emailAddress='$email' OR username='$username'";
        $queryFirst = $this->db->query($sqlFirst);
        if ($queryFirst->num_rows() >= 1) {
            return 0;
        } else {
            $sql = "INSERT INTO Users (username, password, emailAddress, organiser)
                    VALUES ('$username','$password','$email', '$userType')";

            $this->db->query($sql);
            return 1;
        }
    }

    public function setActive($username) {
        $sql = "UPDATE Users SET active=1 WHERE username='$username'";
        $this->db->query($sql);
    }

    public function getResume($username) {
        $sql = "SELECT * FROM Users U, Resumes R WHERE U.username='$username' AND U.id = R.ownerID";
        $query = $this->db->query($sql);

        if($query->num_rows() >= 1){
            return $query->result()[0];
        } else {
            return false;
        }
    }

    public function updateResume($ownerID, $firstName, $lastName, $phoneNo, $descriptionText, $descriptionImageLink, $tags) {
        $sql = "UPDATE Resumes SET lastName='$lastName', firstName='$firstName', phoneNo='$phoneNo', descriptionText='$descriptionText', descriptionImageLink='$descriptionImageLink', tags='$tags' WHERE ownerID = '$ownerID'";
        $query = $this->db->query($sql);
    }

    public function insertResume($ownerID, $firstName, $lastName, $phoneNo, $descriptionText, $descriptionImageLink, $tags) {
        $sql = "INSERT INTO Resumes VALUES ('$ownerID','$lastName','$firstName','$phoneNo','$descriptionText','$descriptionImageLink','$tags')";
        $query = $this->db->query($sql);
    }

    public function getUserInfo($username) {
        $sql = "SELECT * FROM Users WHERE username = '$username'";
        $query = $this->db->query($sql);

        return $query->result()[0];
    }

    public function getParticipations($username, $eventID) {
        $sql = "SELECT * FROM Participations P, Users U WHERE P.eid='$eventID' AND U.username='$username' AND U.id=P.uid";
        $query = $this->db->query($sql);

        if($query->num_rows() >= 1){
            return $query->result()[0];
        } else {
            return false;
        }
    }

    public function insertParticipations($username, $eventID) {
        $uid = $this->getUserInfo($username)->id;
        $sql = "INSERT INTO Participations (eid, uid, status) VALUES ($eventID, $uid, 'Processing')";
        $query = $this->db->query($sql);
    }

    public function getNotDeniedParticipations($eventID) {
        //Not denied
        $sql = "SELECT P.*, U.username, U.emailAddress, R.* FROM Participations P, Users U, Resumes R WHERE P.eid='$eventID' AND P.uid=U.id AND P.uid=R.ownerID AND P.status<>'Denied'";
        $query = $this->db->query($sql);

        if($query->num_rows() >= 1){
            return $query->result();
        } else {
            return false;
        }
    }

    public function setDenied($uid, $eid) {
        $sql = "UPDATE Participations SET status='Denied' WHERE uid='$uid' AND eid='$eid'";
        $query = $this->db->query($sql);
    }

    public function setPassed($uid, $eid) {
        $sql = "UPDATE Participations SET status='Passed' WHERE uid='$uid' AND eid='$eid'";
        $query = $this->db->query($sql);
    }

    public function setAllDenied($eid) {
        $sql = "UPDATE Participations SET status='Denied' WHERE eid='$eid' AND status='Processing'";
        $query = $this->db->query($sql);
    }

    public function getProcessingByEventID($eventID) {
        $sql = "SELECT * FROM Users U, Participations P WHERE P.eid='$eventID' AND P.uid=U.id AND P.status='Processing'";
        $query = $this->db->query($sql);

        if($query->num_rows() >= 1){
            return $query->result();
        } else {
            return false;
        }
    }

    public function deleteOneParticipation($eid, $uid) {
        $sql = "UPDATE Participations SET deletion=1 WHERE uid='$uid' AND eid='$eid' AND status='Processing'";
        $query = $this->db->query($sql);
    }
}
?>