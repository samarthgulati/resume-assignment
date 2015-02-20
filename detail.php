<?php 
require_once "pdo.php";
        $profile_stmt = $pdo->prepare("SELECT u.id_profile, u.first_name, u.last_name, u.email, u.phone, u.headline, u.summary, u.address, u.website, u.picture
                                       FROM Profile as u WHERE u.email = :email LIMIT 1");
        $profile_stmt->execute(array(
                        ':email' => $_GET['email']
                      )
                );
        $user["Profile"]=$profile = $profile_stmt->fetchAll();

        $edu_stmt = $pdo->prepare("SELECT e.school_name, e.field_of_study, e.start_date, e.end_date, e.degree, e.activities, e.notes FROM
                              Education as e  WHERE e.id_profile = :id_profile");
        $edu_stmt->execute(array(
                        ':id_profile' => $profile[0]["id_profile"]
                      )
                );
        $user["Education"] = $edu_stmt->fetchAll();

        $skill_stmt = $pdo->prepare("SELECT s.name FROM
                              Skills as s WHERE s.id_profile = :id_profile");
        $skill_stmt->execute(array(
                        ':id_profile' => $profile[0]["id_profile"]
                      )
                );
        $user["Skills"] = $skill_stmt->fetchAll();

        $job_stmt = $pdo->prepare("SELECT j.title, j.company_name, j.summary, j.start_date, j.end_date, j.is_current FROM
                               Positions as j WHERE j.id_profile = :id_profile");
        $job_stmt->execute(array(
                        ':id_profile' => $profile[0]["id_profile"]
                      )
                );
        $user["Positions"] = $job_stmt->fetchAll();

        $team_stmt = $pdo->prepare("SELECT t.id_project FROM
                                      Team as t WHERE t.id_profile = :id_profile");
        $team_stmt->execute(array(
                        ':id_profile' => $profile[0]["id_profile"]
                      )
                );


        $project_id = $team_stmt->fetchAll();

        $project_stmt = $pdo->prepare("SELECT p.name, p.team_name, p.summary FROM
                                      Projects as p WHERE p.id_project = :id_project");


        foreach ($project_id as $key => $value) {
          
          $project_stmt->execute(array(
                        ':id_project' => $value["id_project"]
                      )
                );
          $pro = $project_stmt->fetchAll();
          $user["Projects"][$key]= $pro[0];
        }

        echo json_encode($user);
