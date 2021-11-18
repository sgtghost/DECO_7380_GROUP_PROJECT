<?php
class Comment extends CI_Controller {
    public function insert() {
        $this->load->model('file_model');
        $this->load->model('user_model');
        $contentText = $this->input->post("contentText");
        $commentEid = $this->input->post("eventID");
        $commentDate = $this->input->post("commentDate");
        $username = $this->session->userdata('username');
        $avatar = $this->session->userdata('avatar');   
        $commenter = $this->user_model->getUserInfo($username)->id;

        $this->file_model->insertComment($commenter, $commentEid, $commentDate, $contentText);
    }

    public function insertReply() {
        $this->load->model('file_model');
        $this->load->model('user_model');
        $replyContent = $this->input->post("replyContent");
        $cid = $this->input->post("commentID");
        $replyDate = $this->input->post("replyDate");
        $username = $this->session->userdata('username');
        $replyUid = $this->user_model->getUserInfo($username)->id;

        $this->file_model->insertReply($cid, $replyUid, $replyDate, $replyContent);
    }

    public function addLike() {
        $this->load->model('file_model');
        $this->load->model('user_model');
        $username = $this->session->userdata('username');
        $commentID = $this->input->post("commentID");
        $comment = $this->file_model->getCommentByID($commentID);

        if ($comment) {
            if ($comment->whoUpvote) {
                //Already have likes
                $whoUpvote = json_decode($comment->whoUpvote)->usernames;
                array_push($whoUpvote, $username);
                $whoUpvote = json_encode(array("usernames"=>$whoUpvote));
            } else {
                $whoUpvote = '{"usernames":["' . $username . '"]}';
            } 
            
            $this->file_model->addCommentUpvote($whoUpvote, $commentID);
        }
    }

    public function subtractLike() {
        $this->load->model('file_model');
        $this->load->model('user_model');
        $username = $this->session->userdata('username');
        $commentID = $this->input->post("commentID");
        $comment = $this->file_model->getCommentByID($commentID);

        if ($comment) {
            $whoUpvote = json_decode($comment->whoUpvote)->usernames;
            foreach ($whoUpvote as $key => $value) {
                if ($value == $username) {
                    unset($whoUpvote[$key]);
                }
            }
            $whoUpvote = json_encode(array("usernames"=>$whoUpvote));
            
            $this->file_model->subtractCommentUpvote($whoUpvote, $commentID);
        }
    }

}

?>