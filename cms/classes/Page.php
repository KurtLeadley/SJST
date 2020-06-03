<?php # Page.php - Script 9.5
// This script defines the Page class.

/*  Class Page.
 *  The class contains six attributes: id, creatorId, title, content, dateAdded, and dateUpdated.
 *  The attributes match the corresponding database columns.
 *  The class contains seven methods: 
 *  - getId()
 *  - getCreatorId()
 *  - getTitle()
 *  - getContent()
 *  - getDateAdded()
 *  - getDateUpdated()
 *  - getIntro()
 */
class Page {
    
    // All attributes correspond to database columns.
    // All attributes are protected.
    protected $userId = null;
 	  protected $id = null;
    protected $creatorId = null;
    protected $title = null;
    protected $content = null;
    protected $dateAdded = null;
    protected $dateUpdated = null;
		protected $image = null;
		protected $alias = null;
		protected $description = null;
		protected $commentTotal = null;
    
    // No need for a constructor!

    // Six methods for returning attribute values:
    function getUserId() {
      return $this->userId;
    }   	
		
		function getId() {
        return $this->id;
    }

		function getcommentTotal() {
      return $this->total;
    } 
		
		function getAlias() {
      return $this->alias;
    } 
		function getDesc() {
      return $this->description;
    }		
    function getCreatorId() {
        return $this->creatorId;
    }   
    function getTitle() {
        return $this->title;
    }
    function getContent() {
        return $this->content;
    }
    function getDateAdded() {
        return $this->dateAdded;
    }   
    function getDateUpdated() {
        return $this->dateUpdated;
    }   
    
    // Method returns the first X characters from the content:
    function getIntro($count = 100) {
        return substr(strip_tags($this->content), 0, $count) . '...';
    }
		
		function getImage() {
        return $this->image;
    } 
    
} // End of Page class.