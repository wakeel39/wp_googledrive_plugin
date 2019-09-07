<?php global $result2;  ?>
<style type="text/css">

#payment  input[type="text"], textarea {
width: 81%;  }
#payment {
 width: 70%;
   
    box-shadow: 2px 2px 2px #ccc;
    padding: 10px;
    border-radius: 10px;
}

</style>
<form name="payment" id="payment" onsubmit="return(formvalidate());" method="post" action="<?php $_SERVER['PHP_SELF']; ?>"  style="">


<table width="100%" border="0">



  <tr>
    <td align="right" style="width:25%;"> Name </td>
    <td width="56%"><input type="text" name="name" id="name" placeholder="Name" value="<?php echo $result2->name; ?>"/><div id="name_error"></div></td>

    
 </tr>
  <tr>
    <td align="right" style="width:25%;"> Permissions </td>
    <td width="56%"><?php if(!empty($result2->permissions)) { $per = explode(",",$result2->permissions); } else { $per = array();   }   ?>
	<input type="checkbox" value="View" <?php if(in_array('View', $per)) { echo "checked"; } else { }   ?> name="per['View']" id="per[]" placeholder="Name"/>View
	<input type="checkbox" value="Edit" <?php if(in_array('Edit', $per)) { echo "checked"; }  ?> name="per['Edit']" id="per[]" placeholder="Name"/>Edit
	<input type="checkbox" value="Delete" <?php if(in_array('Delete', $per)) { echo "checked"; }  ?> name="per['Delete']" id="per[]" placeholder="Name"/>Delete
	<input type="checkbox" value="Create" <?php if(in_array('Create', $per)) { echo "checked"; }  ?> name="per['Create']" id="per[]" placeholder="Name"/>Create
	<input type="checkbox" value="Share" <?php if(in_array('Share', $per)) { echo "checked"; }  ?> name="per['Share']" id="per[]" placeholder="Name"/>Share
	<div id="name_error"></div></td>

 </tr>
     
  <tr>
    <td colspan="2" align="center"><input style="width:20%; " type="submit" name="update_info" value="Submit">
  
  </tr>
</table>
</form>