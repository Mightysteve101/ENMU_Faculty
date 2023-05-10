<?php

    if(empty($primaryUnitKey))
    {
        header("Location: https://www.enmu.edu/");
        die();
    }
    else
    {
        $servername = 'localhost';
        $username = 'enmu';
        $password = 'HpSecfQRlYXBa2M';
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
        $getTaggedEmployeeInfo = 'SELECT spriden_pidm, pwbcdir_image, spriden_first_name, spriden_last_name, pwbcdir_title, goremal_email_address, pwbcdir_building, pwbcdir_room_numb, pwbcdir_phone, spbpers_name_prefix, pwbcdir_office_hours, pwbcdir_vitae, pwbcdir_url, pwbcdir_education, pwbcdir_biography, pwbcdir_research_interests, pwbcdep_department FROM enmu_directory.pwbcdir LEFT OUTER JOIN enmu_directory.spriden ON pwbcdir_pidm = spriden_pidm LEFT OUTER JOIN enmu_directory.spbpers ON spriden_pidm = spbpers_pidm LEFT OUTER JOIN enmu_directory.goremal ON spbpers_pidm = goremal_pidm LEFT OUTER JOIN enmu_directory.pwbcdep ON pwbcdep_dept_index = pwbcdir_dept_index WHERE pwbcdir_dept_index = ' . $primaryUnitKey . ' ORDER BY spriden_last_name, spriden_first_name';
        $result = $conn->query($getTaggedEmployeeInfo);
        
        // Handle data
            
            //while there is a connection to mysqli grab properties
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $pidm = $row['spriden_pidm'];
                $firstName = $row['spriden_first_name'];
                if (strlen($firstName) <= 0 and $testing)
                    $firstName = '[missing first name]';
                $lastName = $row['spriden_last_name'];
                if (strlen($lastName) <= 0 and $testing)
                    $lastName = '[missing last name]';
                if (strlen($row['pwbcdir_image']) > 0)
                    $imageUrl = $row['pwbcdir_image'];
                else
                    $imageUrl = '/images/faculty-staff/images/default.jpg';
                $title = $row['pwbcdir_title'];
                if (strlen($row['pwbcdir_title']) > 0)
                    $title = $row['pwbcdir_title'];
                else
                    $title = '[Missing data]';
                $ext = $row['pwbcdir_phone'];
                if (strlen($row['pwbcdir_phone']) > 0)
                    $ext = $row['pwbcdir_phone'];
                else
                    $ext = ['Missing data'];
                $biography = $row['pwbcdir_biography'];
                $research = $row['pwbcdir_research_interests'];
                $prefix = $row['spbpers_name_prefix'];
                if (strlen($row['pwbcdir_building']) > 0)
                    $buildingCode = $row['pwbcdir_building'];
                else
                    $buildingCode = '[Missing data]';
                if (strcmp($buildingCode, '[Missing data]') != 0) {
                    $buildingGet = $conn->query('SELECT buildings_full_name FROM enmu_directory.buildings WHERE buildings_code = "' . $buildingCode . '"');
                    $buildingRow = $buildingGet->fetch_assoc();
                    $buildingFull = $buildingRow['buildings_full_name'];
                    if (strlen($buildingFull) < 1)
                        $buildingFull = '[Building Name TBD]';
                } else
                    $buildingFull = '[Building Name TBD]';
                if (strlen($row['pwbcdir_room_numb']) > 0)
                    $roomNumber = $row['pwbcdir_room_numb'];
                else
                    $roomNumber = '[Missing data]';
                $email = $row['goremal_email_address'];
                if (strlen($row['goremal_email_address']) > 0)
                    $email = $row['goremal_email_address'];
                else
                    $email = ['Missing data'];
                $tagName = $row['pwbtags_tag_desc'];

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
				if (strlen($row['pwbcdir_education']) > 0){
				echo '<h3>Education</h3>';
                $educationArray = explode("\n", $row['pwbcdir_education']);
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
                if (strlen($row['pwbcdir_biography']) > 0) {
                    echo '<h3>Bio</h3>';
                    $bioArray = explode("\n", $row['pwbcdir_biography']);
                    foreach ($bioArray as $item) {
						if (strlen($item) > 0){
                        echo '<p>' . $item . '</p>';}
                    }
                }

                /*Research Interest */
                if (strlen($row['pwbcdir_research_interests']) > 0) {
                    echo '<h3>Research Interests</h3>';
                    $researchInterestsArray = explode("\n", $row['pwbcdir_research_interests']);
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
