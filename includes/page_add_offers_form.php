<?php function googledriveForm() { ?>
<style type="text/css">

#payment  input[type="text"], textarea {
width: 81%;  }
#payment {
 width: 70%;
    border: 1px solid #ccc;
   /* box-shadow: 2px 2px 2px #ccc; */
    padding: 10px;
    border-radius: 10px;
	font-weight:bold; background-color:#fff;
}
#payment  input type['submit'] { background:#0066CC; }  

</style>
<form name="payment" id="payment" onsubmit="return(formvalidate());" method="post" action="<?php $_SERVER['PHP_SELF']; ?>"  style="">


<table width="100%" border="0">



  <tr>
    <td align="right" style="width:25%;"> Name </td>
    <td width="56%"><input type="text" name="name" id="name" placeholder="Name"/><div id="name_error"></div></td>

    
 </tr>
 <tr>
    <td align="right" style="width:25%;"> Permissions </td>
    <td width="56%">
	<input type="checkbox" name="per['View']" value="View" id="per[]" placeholder="Name"/>View
	<input type="checkbox" name="per['Edit']" value="Edit"  id="per[]" placeholder="Name"/>Edit
	<input type="checkbox" name="per['Delete']" value="Delete"  id="per[]" placeholder="Name"/>Delete
	<input type="checkbox" name="per['Create']" value="Create"  id="per[]" placeholder="Name"/>Create
	<input type="checkbox" name="per['Share']" value="Share"  id="per[]" placeholder="Name"/>Share
	<div id="name_error"></div></td>

 </tr>
      
  <tr>
    <td colspan="2" align="center"><input style="width:20%; " type="submit" name="insertinto" value="Submit">
  
  </tr>
</table>
</form>
<?php  } ?>