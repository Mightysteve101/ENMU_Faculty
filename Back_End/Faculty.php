<?php

    if(empty($primaryUnitKey))
    {
        header("Location: enter link");
        die();
    }
    else
    {
        $servername = 'enter host name';
        $username = 'enter username';
        $password = 'enter password';
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error)
        {
            echo 'Connection failed: ' . $conn->connect_error;
        }
        else
        {
        //Select person indicator, first name, last name, prefix, and email based on department and tag
        $getTaggedEmployeeInfo = 'SELECT person_id, dir_image, person_first_name, person_last_name, dir_title, email_address, dir_building, dir_room_numb, dir_phone, person_name_prefix, dir_office_hours, 
	dir_vitae, dir_url, dir_education, dir_biography, dir_research_interests, dep_department FROM school_directory.dir LEFT OUTER JOIN school_directory.person ON dir_id = person_id LEFT OUTER JOIN 
 	school_directory.prefix ON person_id = prefix_id LEFT OUTER JOIN school_directory.email ON prefix_id = email_id LEFT OUTER JOIN school_directory.dep ON dep_dept_index = dir_dept_index WHERE 
  	dir_dept_index = ' . $primaryUnitKey . ' ORDER BY person_last_name, person_first_name';
        $result = $conn->query($getTaggedEmployeeInfo);
        
        // Handle data
            
            //while there is a connection to mysqli grab properties
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $pidm = $row['person_id']; 
                $firstName = $row['person_first_name']; 
                if (strlen($firstName) <= 0 and $testing)
                    $firstName = '[missing first name]';
                $lastName = $row['person_last_name']; 
                if (strlen($lastName) <= 0 and $testing)
                    $lastName = '[missing last name]';
                if (strlen($row['dir_image']) > 0) 
                    $imageUrl = $row['dir_image']; 
                else
                    $imageUrl = 'dir path to image';
                $title = $row['dir_title']; 
                if (strlen($row['dir_title']) > 0)
                    $title = $row['dir_title'];
                else
                    $title = '[Missing data]';
                $ext = $row['dir_phone']; 
                if (strlen($row['dir_phone']) > 0)
                    $ext = $row['dir_phone'];
                else
                    $ext = ['Missing data'];
                $biography = $row['dir_biography']; 
                $research = $row['dir_research_interests']; 
                $prefix = $row['person_name_prefix']; 
                if (strlen($row['dir_building']) > 0) 
                    $buildingCode = $row['dir_building'];
                else
                    $buildingCode = '[Missing data]';
                if (strcmp($buildingCode, '[Missing data]') != 0) {
                    $buildingGet = $conn->query('SELECT buildings_name FROM directory.buildings WHERE buildings_code = "' . $buildingCode . '"');
                    $buildingRow = $buildingGet->fetch_assoc();
                    $buildingFull = $buildingRow['buildings_name'];
                    if (strlen($buildingFull) < 1)
                        $buildingFull = '[Building Name TBD]';
                } else
                    $buildingFull = '[Building Name TBD]';
                if (strlen($row['dir_room_numb']) > 0) 
                    $roomNumber = $row['dir_room_numb'];
                else
                    $roomNumber = '[Missing data]';
                $email = $row['email_address']; 
                if (strlen($row['email_address']) > 0)
                    $email = $row['email_address'];
                else
                    $email = ['Missing data'];
                $tagName = $row['tag_desc']; 
                //Now that the data has been handled

                //Button Click info
                echo '<div class="col-sm-6 col-md-4 mb-4">';
                echo '<input type="image" src="' . $imageUrl . '" alt = "' . $firstName . $lastName . ' class="dir-readon" data-bs-toggle="modal" data-bs-target="#exampleModal' . $pidm . '">';
                echo '<button type="button" class="dir-readon" data-bs-toggle="modal" data-bs-target="#exampleModal' . $pidm . '">';
				echo '<h4>';
				if (strcmp($row['spbpers_name_prefix'],'Dr.') == 0) echo $row['spbpers_name_prefix'].' ';
                echo $firstName . ' ' . $lastName . '</h4>';
                echo '<p class="title-caption">' . $title . '</p>';
                echo '</button>';
                echo '</div>';

                /*Modal*/
                echo '<div class="modal fade" id="exampleModal' . $pidm . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                echo '<div class="modal-dialog modal-xl">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="exampleModalLabel"></h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                echo '</div>';

                /*Modal Body*/

                echo '<div class="modal-body">';
                echo '<div class="row align-items-top">';
                echo '<div class="col-sm-12 col-md-4">';
                echo '<img src= "' . $imageUrl . '" alt = "' . $firstName . $lastName . '"/>';
                echo '</div>';
                echo '<div class="col-sm-12 col-md-4">';
                echo '<h3>' . $firstName . " " . $lastName . '</h3>';
                echo '<p class="description-modal"><strong>Title: </strong>' . $title . '</p>';
                echo '<p class="description-modal"><strong>Office Location: </strong>' . $buildingCode . " " . $roomNumber . '</p>';
                echo '<p class="description-modal"><strong>Phone: </strong>575.562.' . $ext . '</p>';
                echo '<a href = "mailto: ' . $email . '">' . $email . '</a>';
                echo '</div>';
                
                echo '<div class="col-sm-12 col-md-4">';


                /* Creates and array of characters everytime there is a line break then prints them */
				if (strlen($row['dir_education']) > 0){
				echo '<h3>Education</h3>';
                $educationArray = explode("\n", $row['dir_education']);
                foreach ($educationArray as $item) {
					/*if statement here checks if the line item within the array is not blank, and will post the paragraph if it is not */
					if (strlen($item) > 0){
                    echo '<p class="p-modal">' . $item . '</p>';}
					}
				}

                echo '</div>';
                echo '<div class="row">';
                echo '<div class="col-sm-12 md-4">';

                /*Bio*/
                if (strlen($row['dir_biography']) > 0) {
                    echo '<h3>Bio</h3>';
                    $bioArray = explode("\n", $row['dir_biography']);
                    foreach ($bioArray as $item) {
						if (strlen($item) > 0){
                        echo '<p>' . $item . '</p>';}
                    }
                }

                /*Research Interest */
                if (strlen($row['dir_research_interests']) > 0) {
                    echo '<h3>Research Interests</h3>';
                    $researchInterestsArray = explode("\n", $row['dir_research_interests']);
                    foreach ($researchInterestsArray as $item) {
						if (strlen($item) > 0){
                        echo '<p>' . $item . '</p>';}
                    }
                }

                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                /*End of Modal Body */
                echo '<div class="modal-footer">';
                echo '<button type="button" class="readon" data-bs-dismiss="modal">Close</button>';
                echo '</div>';
                /* End of modal */
                echo '</div>';
                echo '</div>';
                echo '</div>';

            }

        }
    }
        $conn->close();
}
?>
