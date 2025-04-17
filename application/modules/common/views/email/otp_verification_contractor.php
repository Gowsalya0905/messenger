<?php $loginURL = (isset($loginURL) && $loginURL !='') ? $loginURL : BASE_URL;?> 
<tr>
                    <td bgcolor="#ffffff" style="padding: 30px 30px 40px 30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 18px;">
                                    <b>Hello <?php echo $name;?>!</b>
                                </td>
                            </tr>
                            <tr><td><br></td></tr>
                            <tr>
                                <td>
                                    <?php echo  $messageText;?>
                                     
                                </td>
                            </tr>
                            
                          
                            <tr><td><?php echo $footerText;?></td></tr>
                        </table>
                    </td>
                </tr>