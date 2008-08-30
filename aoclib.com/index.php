<?php
/* INDEX.PHP */

include('functions.php');

$_USER = checkLogin();

head(false, 'News', $_USER);
box(0);

echo 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus pharetra enim non ipsum. Proin feugiat vehicula elit. Donec elit. Vestibulum sed dui. Nunc quam lorem, ultricies vitae, tempor in, ultrices at, odio. Integer in nibh in sapien congue placerat. Cras lectus. Donec quis nunc. In hac habitasse platea dictumst. Sed non metus id dui ultrices consequat. Etiam scelerisque, massa sollicitudin tincidunt pharetra, nulla velit imperdiet urna, vitae posuere est sem nec metus. Pellentesque ornare, diam mattis tempus sagittis, est magna cursus nisl, non semper massa massa ut diam. Maecenas et massa in odio vulputate aliquet. Suspendisse et eros. Curabitur nibh quam, pharetra vitae, vehicula sit amet, rhoncus eu, quam. Aliquam nec diam. Curabitur massa ligula, vehicula aliquet, viverra sodales, ornare non, tortor. Suspendisse potenti. Donec ut eros.

Pellentesque dolor lacus, venenatis vel, ultricies eu, vestibulum sit amet, ante. Morbi vehicula velit ornare odio. Nam sed augue et ipsum elementum pulvinar. Morbi cursus. Vestibulum viverra tincidunt nisi. Phasellus mollis malesuada nisi. Nunc elit. Proin sagittis dolor adipiscing libero. Praesent congue dapibus magna. Proin eleifend. Phasellus et ante vitae eros sollicitudin convallis. Proin in quam. Duis in est.';

box(1);
foot();

//user ranks
//0-9    User
//10-19  Content Manager
//20-29  Moderator
//30-39  Administrator
//40     Developer
?>