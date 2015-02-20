<?php
require_once "pdo.php";
// Helper method to get a string description for an HTTP status code
// From http://www.gen-x-design.com/archives/create-a-rest-api-with-php/ 
function getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    return (isset($codes[$status])) ? $codes[$status] : '';
}

// Helper method to send a HTTP response code/message
function sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    $status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMessage($status);
    header($status_header);
    header('Content-type: ' . $content_type);
    echo $body;
}

 
class ProfileAPI {

    /*private $db;

    // Constructor - open DB connection
    function __construct() {
        $this->db = new mysqli('localhost', 'username', 'password', 'promos');
        $this->db->autocommit(FALSE);
    }

    // Destructor - close DB connection
    function __destruct() {
        $this->db->close();
    }*/

    function insertProfile(){
    $sql = "INSERT INTO Profile (first_name, last_name, email, phone, headline, summary, address, website, picture) 
    VALUES (:first_name, :last_name, :email, :phone, :headline, :summary, :address, :website, :picture)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':first_name'=>$_POST['first_name'], 
                         ':last_name'=>$_POST['last_name'], 
                         ':email'=>$_POST['email'], 
                         ':phone'=>$_POST['phone'], 
                         ':headline'=>$_POST['headline'], 
                         ':summary'=>$_POST['summary'], 
                         ':address'=>$_POST['address'], 
                         ':website'=>$_POST['website'], 
                         ':picture'=>$_POST['picture']));
    }
    
    function insertEducation(){
        $sql = "INSERT INTO Education (school_name, field_of_study, start_date, end_date, degree, activities, notes, id_profile) 
        VALUES (:school_name, :field_of_study, :start_date, :end_date, :degree, :activities, :notes, :id_profile)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':school_name'=>$_POST['school_name'], 
                             ':field_of_study'=>$_POST['field_of_study'], 
                             ':start_date'=>$_POST['start_date'], 
                             ':end_date'=>$_POST['end_date'], 
                             ':degree'=>$_POST['degree'], 
                             ':activities'=>$_POST['activities'], 
                             ':notes'=>$_POST['notes'], 
                             ':id_profile'=>$_POST['id_profile']));
    }
    
    function insertSkill(){
        $sql = "INSERT INTO Skill (name, id_profile) 
        VALUES (:name, :id_profile)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':name'=>$_POST['name'],
                             ':id_profile'=>$_POST['id_profile']));
    }
    
    function insertPosition(){
        $sql = "INSERT INTO Position (title, company_name, summary, start_date, end_date, is_current, id_profile) 
        VALUES (:title, :company_name, :summary, :start_date, :end_date, :is_current, :id_profile)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':title'=>$_POST['title'], 
                             ':company_name'=>$_POST['company_name'], 
                             ':summary'=>$_POST['summary'], 
                             ':start_date'=>$_POST['start_date'], 
                             ':end_date'=>$_POST['end_date'], 
                             ':is_current'=>$_POST['is_current'], 
                             ':id_profile'=>$_POST['id_profile']));
    }
    
    function insertProject(){
        $sql = "INSERT INTO Project (name, team_name, summary, initiation, planning, execution, monitoring_control, closure) 
        VALUES (:name, :team_name, :summary, :initiation, :planning, :execution, :monitoring_control, :closure)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':name'=>$_POST['name'], 
                             ':team_name'=>$_POST['team_name'], 
                             ':summary'=>$_POST['summary'], 
                             ':initiation'=>$_POST['initiation'], 
                             ':planning'=>$_POST['planning'], 
                             ':execution'=>$_POST['execution'], 
                             ':monitoring_control'=>$_POST['monitoring_control'], 
                             ':closure'=>$_POST['closure']));
    }
    
    function insertTeam(){
        $sql = "INSERT INTO Team (id_profile, id_project) 
        VALUES (:id_profile, id_project)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id_profile'=>$_POST['id_profile'],
                             ':id_project'=>$_POST['id_project']));
    }

    function getUser(){
        $stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.email, u.phone, u.headline, u.summary, u.address, u.website, u.picture/*,
                                      e.school_name, e.field_of_study, e.start_date, e.end_date, e.degree, e.activities, e.notes,
                                      s.name,
                                      j.title, j.company_name, j.summary, j.start_date, j.end_date, j.is_current,
                                      p.name, p.team_name, p.summary,
                                      t.id_profile, t.id_project */FROM
                              Profile as u /*LEFT JOIN Education as e on u.id_profile = e.id_profile
                                           LEFT JOIN Skills as s on s.id_profile = u.id_profile
                                           LEFT JOIN Positions as j on j.id_profile = u.id_profile
                                           LEFT JOIN Projects as p on p.id_profile = u.id_profile
                                           LEFT JOIN Team as t on t.id_profile = u.id_profile*/
                              WHERE u.email = :email
                              LIMIT 1");
        $stmt->execute(array(
                        ':email' => $_GET['email']
                      )
                );
        echo json_encode($stmt->fetchAll());
    }

    //sendResponse(200, json_encode(getUser()));

}

// This is the first thing that gets called when this page is loaded
// Creates a new instance of the ProfileAPI class and calls the Profile method
$api = new ProfileAPI;
$api->getUser();
