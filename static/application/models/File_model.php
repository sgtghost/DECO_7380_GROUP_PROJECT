<?php
class File_model extends CI_Model {

    public function getAllEvent($searchVal, $areaVal){
        if ($searchVal) {
            $sql = "SELECT A.*, IFNULL(B.applied, 0) AS 'applied' FROM (SELECT E.*, U.username AS 'organiserName' 
                    FROM Events E, Users U 
                    WHERE E.organiser = U.id 
                    AND E.status = 'ongoing'
                    AND E.requirement LIKE '%$areaVal%'
                    AND (U.username LIKE '%$searchVal%' 
                    OR E.address LIKE '%$searchVal%'
                    OR substring_index(E.descriptionText, 'description', 1) LIKE '%$searchVal%')
                    LIMIT 0, 6) AS A 
                    LEFT JOIN (SELECT eid, COUNT(*) AS 'applied' FROM Participations WHERE status='Passed' GROUP BY eid) AS B 
                    ON A.eventID = B.eid";
        } else {
            $sql = "SELECT A.*, IFNULL(B.applied, 0) AS 'applied' FROM (SELECT E.*, U.username AS 'organiserName' 
                    FROM Events E, Users U 
                    WHERE E.organiser = U.id 
                    AND E.status = 'ongoing'
                    AND E.requirement LIKE '%$areaVal%'
                    LIMIT 0, 6) AS A 
                    LEFT JOIN (SELECT eid, COUNT(*) AS 'applied' FROM Participations WHERE status='Passed' GROUP BY eid) AS B 
                    ON A.eventID = B.eid";
        }     
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getMoreEvent($count, $searchVal, $areaVal) {
        $count *= 5; 

        if ($searchVal) {
            $sql = "SELECT A.*, IFNULL(B.applied, 0) AS 'applied' FROM (SELECT E.*, U.username AS 'organiserName' 
                    FROM Events E, Users U 
                    WHERE E.organiser = U.id 
                    AND E.status = 'ongoing'
                    AND E.requirement LIKE '%$areaVal%'
                    AND (U.username LIKE '%$searchVal%' 
                    OR E.address LIKE '%$searchVal%'
                    OR substring_index(E.descriptionText, 'description', 1) LIKE '%$searchVal%')
                    LIMIT $count, 6) AS A 
                    LEFT JOIN (SELECT eid, COUNT(*) AS 'applied' FROM Participations WHERE status='Passed' GROUP BY eid) AS B 
                    ON A.eventID = B.eid";
        } else {
            $sql = "SELECT A.*, IFNULL(B.applied, 0) AS 'applied' FROM (SELECT E.*, U.username AS 'organiserName' 
                    FROM Events E, Users U 
                    WHERE E.organiser = U.id
                    AND E.status = 'ongoing'
                    AND E.requirement LIKE '%$areaVal%' 
                    LIMIT $count, 6) AS A 
                    LEFT JOIN (SELECT eid, COUNT(*) AS 'applied' FROM Participations WHERE status='Passed' GROUP BY eid) AS B 
                    ON A.eventID = B.eid";
        }  
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getEventByID($eventID) {
        if ($eventID) {
            $sql = "SELECT E.*, U.username AS 'organiserName' FROM Events E, Users U WHERE eventID = $eventID AND E.organiser = U.id";
        } else {
            $sql = "SELECT E.*, U.username AS 'organiserName' FROM Events E, Users U WHERE eventID = 2 AND E.organiser = U.id";
        }
        $query = $this->db->query($sql);
        if($query->num_rows() == 1){
            return $query->result();
        } else {
            return false;
        }
    }

    public function getTopEvent() {
        $sql = "SELECT * FROM Events WHERE status='ongoing' ORDER BY clickCount DESC, eventID ASC LIMIT 4";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getUserMyEvents($username) {
        $sql = "SELECT E.*, P.status AS 'applyStatus' FROM Events E, Users U, Participations P 
                WHERE U.username='$username' 
                AND U.id=P.uid
                AND P.eid = E.eventID 
                AND E.status='ongoing'
                AND P.status<>'Denied'
                AND P.deletion=0";
        $query = $this->db->query($sql);
        if($query->num_rows() >= 1){
            return $query->result();
        } else {
            return false;
        }   
    }

    public function getOrganiserMyEvents($username) {
        $sql = "SELECT E.* FROM Events E, Users U
                WHERE U.username='$username' 
                AND U.id=E.organiser
                AND (E.status='ongoing' OR E.status='private')";
        $query = $this->db->query($sql);

        if($query->num_rows() >= 1){
            return $query->result();
        } else {
            return false;
        }
    }

    public function updateClickCount($eventID) {
        $sql = "UPDATE Events SET clickCount = clickCount+1 WHERE eventID='$eventID'";
        $query = $this->db->query($sql);
    }

    public function getOrganiserByEvent($eventID) {
        $sql = "SELECT R.*, U.* FROM Resumes R, Events E, Users U WHERE R.ownerID = E.organiser AND E.eventID = '$eventID' AND U.id = R.ownerID";
        $query = $this->db->query($sql);

        return $query->result();
    }

    //Get the avatar name
    public function getAvatar($username) {
        $sql = "SELECT R.descriptionImageLink, U.organiser FROM Users U, Resumes R WHERE U.username = '$username' AND U.id = R.ownerID";
        $query = $this->db->query($sql);
        
        if($query->num_rows() == 1){
            return $query->result();
        } else {
            return false;
        }
    }

    public function updateEvent($eventID, $organiser, $address, $coordinates, $date, $descriptionText, $descriptionImageLink, $requirement) {
        $sql = "UPDATE Events SET organiser = $organiser, address='$address', coordinates='$coordinates', date='$date', descriptionText='$descriptionText', descriptionImageLink='$descriptionImageLink', requirement='$requirement' WHERE eventID=$eventID";
        $query = $this->db->query($sql);

        return $query;
    }

    public function insertEvent($organiser, $status, $address, $coordinates, $date, $eventSlot, $descriptionText, $descriptionImageLink, $requirement) {
        $sql1 = "INSERT INTO Events (organiser, status, address, coordinates, date, slot, descriptionText, descriptionImageLink, requirement) VALUES ($organiser,'$status','$address','$coordinates','$date','$eventSlot','$descriptionText','$descriptionImageLink','$requirement')";
        $sql2 = "SELECT MAX(eventID) AS eventID FROM Events";
        $query1 = $this->db->query($sql1);

        if ($query1) {
            $query2 = $this->db->query($sql2);
            return $query2->result()[0];
        } else {
            return false;
        }
    }

    public function getOrganiserManageEvents($username, $status) {
        $sql = "SELECT A.*, IFNULL(B.applying, 0) AS 'applying' FROM (SELECT E.*
                FROM Events E, Users U 
                WHERE E.organiser = U.id 
                AND E.status = '$status'
                AND U.username = '$username') AS A 
                LEFT JOIN (SELECT eid, COUNT(*) AS 'applying' FROM Participations WHERE status='Processing' AND deletion=0 GROUP BY eid) AS B 
                ON A.eventID = B.eid";
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function eventToPublic($eventID) {
        $sql = "UPDATE Events SET status='ongoing' WHERE eventID='$eventID'";
        $query = $this->db->query($sql);
    }

    public function setEventEnded($eventID) {
        $sql = "UPDATE Events SET status='ended' WHERE eventID='$eventID'";
        $query = $this->db->query($sql);
    }

    public function getPassedEvents($username) {
        $sql = "SELECT U.*, E.* FROM Users U, Participations P, Events E WHERE U.username='$username' AND U.id=P.uid AND P.eid=E.eventID AND P.status='Passed' AND E.status<>'private'";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }


    public function getAllApplyEvents($username) {
        $sql = "SELECT U.*, E.*, P.status AS 'applyStatus', P.deletion FROM Users U, Participations P, Events E WHERE U.username='$username' AND U.id=P.uid AND P.eid=E.eventID AND E.status<>'private'";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getCommentsByEvent($eventID) {
        $sql = "SELECT C.*, U.*, R.descriptionImageLink FROM Comments C, Users U, Resumes R WHERE C.commentEid='$eventID' AND C.commenter=U.id AND R.ownerID=U.id ORDER BY C.upvote DESC, C.commentDate DESC";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function insertComment($commenter, $commentEid, $commentDate, $contentText) {
        $sql = "INSERT INTO Comments (commenter, commentEid, commentDate, contentText) VALUES ('$commenter', '$commentEid', '$commentDate', '$contentText')";
        $query = $this->db->query($sql);
    }

    public function getCommentByID($commentID) {
        $sql = "SELECT * FROM Comments WHERE commentID='$commentID'";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result()[0];
        } else {
            return false;
        }
    }

    public function addCommentUpvote($whoUpvote, $commentID) {
        $sql = "UPDATE Comments SET whoUpvote='$whoUpvote', upvote=upvote+1 WHERE commentID='$commentID'";
        $query = $this->db->query($sql);
    }

    public function subtractCommentUpvote($whoUpvote, $commentID) {
        $sql = "UPDATE Comments SET whoUpvote='$whoUpvote', upvote=upvote-1 WHERE commentID='$commentID'";
        $query = $this->db->query($sql);
    }

    public function getRepliesByEvent($eventID) {
        $sql = "SELECT RE.*, U.*, R.descriptionImageLink FROM Replies RE, Users U, Resumes R, Comments C WHERE C.commentEid='$eventID' AND C.commentID=RE.cid AND RE.replyUid=U.id AND RE.replyUid=R.ownerID ORDER BY RE.replyDate DESC";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function insertReply($cid, $replyUid, $replyDate, $replyContent) {
        $sql = "INSERT INTO `Replies`(cid, replyUid, replyDate, replyContent) VALUES ('$cid','$replyUid','$replyDate','$replyContent')";
        $query = $this->db->query($sql);
    }
}
?>