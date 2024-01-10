
<?php
include('../includes/dbcon.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "SELECT image, audio, video from ticket where srno='$id' ";
    $run = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC);

    $response = array();

    // if (!empty($row['image'])) {
    //     $response['type'] = 'image';
    //     $response['content'] = '<img src="../file/image-upload/' . $row['image'] . '" width="250" height="250">';
    // } else if (!empty($row['audio'])) {
    //     $response['type'] = 'audio';
    //     $response['content'] = '<audio width="500" height="100" controls>
    //                             <source src="../file/audio-upload/' . $row['audio'] . '" type="audio/mp3">
    //                             Your browser does not support the audio element.
    //                             </audio>';
    // } else if (!empty($row['video'])) {
    //     $response['type'] = 'video';
    //     $response['content'] = '<video id="vid" width="250" height="250" controls>
    //                             <source src="../file/video-upload/' . $row['video'] . '" type="video/mp4">
    //                             Your browser does not support the video tag.
    //                             </video>';
    // } else {

    //     $response['content'] = ''; // You can set a default message or leave it empty.
    // }

    if (!empty($row['image'])) {
      $response[] = array(
          'type' => 'image',
          'content' => '<img class="mt-2" src="../file/image-upload/' . $row['image'] . '" width="250" height="250">'
      );
  }

  if (!empty($row['audio'])) {
      $response[] = array(
          'type' => 'audio',
          'content' => '<audio class="mt-4"  width="500" height="100" controls>
                          <source src="../file/audio-upload/' . $row['audio'] . '" type="audio/mp3">
                          Your browser does not support the audio element.
                          </audio>'
      );
  }

  if (!empty($row['video'])) {
      $response[] = array(
          'type' => 'video',
          'content' => '<video class="mt-4" id="vid" width="250" height="250" controls>
                          <source src="../file/video-upload/' . $row['video'] . '" type="video/mp4">
                          Your browser does not support the video tag.
                          </video>'
      );
  }

  // If no content is available, you can set a default message
  if (empty($response)) {
      $response[] = array(
          'type' => 'text',
          'content' => 'No content available.'
      );
  }

    // Convert the response array to JSON format
    echo json_encode($response);
}
?>
