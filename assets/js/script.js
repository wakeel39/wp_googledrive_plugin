jQuery(function () {

var filemanager = jQuery('.filemanager'),
        breadcrumbs = jQuery('.breadcrumbs'),
        fileList = filemanager.find('.data');
        var c=false;
GetAllData(0);

// Start by fetching the file data from scan.php with an AJAX request
function GetAllData(t_name) {

    jQuery.get(ajaxurl + "?action=my_action&param=hello", function (data) {

        var response = [data],
                currentPath = '',
                breadcrumbsUrls = [];
        var folders = [],
                files = [];
        // This event listener monitors changes on the URL. We use it to
        // capture back/forward navigation in the browser.

        jQuery(window).on('hashchange', function () {

            goto(window.location.hash);
            // We are triggering the event. This will execute 
            // this function on page load, so that we show the correct folder:

        }).trigger('hashchange');
        // Hiding and showing the search box

        filemanager.find('.search').click(function () {

            var search = jQuery(this);
            search.find('span').hide();
            search.find('input[type=search]').show().focus();
        });
        // Listening for keyboard input on the search field.
        // We are using the "input" event which detects cut and paste
        // in addition to keyboard input.

        filemanager.find('input').on('input', function (e) {

            folders = [];
            files = [];
            var value = this.value.trim();
            if (value.length) {

                filemanager.addClass('searching');
                // Update the hash on every key stroke
                window.location.hash = 'search=' + value.trim();
            }

            else {

                filemanager.removeClass('searching');
                window.location.hash = encodeURIComponent(currentPath);
            }

        }).on('keyup', function (e) {
            // Clicking 'ESC' button triggers focusout and cancels the search
            var search = jQuery(this);
            if (e.keyCode == 27) {

                search.trigger('focusout');
            }

        }).focusout(function (e) {

                // Cancel the search

            var search = jQuery(this);
            if (!search.val().trim().length) {

                window.location.hash = encodeURIComponent(currentPath);
                search.hide();
                search.parent().find('span').show();
            }

        });
       /*   fileList.on('click', '.del_folder', function (e) {
            e.preventDefault();
            var id = jQuery(this).attr('id');
        if(t_name == '0') {
            var a = confirm("Are You Sure You want Delete this Folder!");
           
            if(a === true)
             {
                jQuery('#preloader_status').show();
                jQuery('#preloader').show();
                jQuery.ajax({
                    url: ajaxurl+"?action=del_folder&id="+id,
                    type: 'GET',
                 
                    contentType: false,
                    processData: false,
                    success: function (returndata) {
                        //alert(returndata);
                       
                        GetAllData(1);
                        jQuery('#preloader_status').hide();
                        jQuery('#preloader').hide();
                        
                    }
                });
              //window.location.href = url;
             }
         }
             c = true;
            
         });

        
       fileList.on('click', '.del_file', function (e) {
             e.preventDefault();
            var url = jQuery(this).attr('href');
            var id = jQuery(this).attr('id');
            if(t_name == '0') {
            var a = confirm("Are You Sure You want Delete this file!");
             
           if(a === true)
             {
                jQuery('#preloader_status').show();
                jQuery('#preloader').show();
                jQuery.ajax({
                    url: ajaxurl+"?action=del_file&id="+id,
                    type: 'GET',
                 
                    contentType: false,
                    processData: false,
                    success: function (returndata) {
                        //alert(returndata);
                        GetAllData(1);
                        jQuery('#preloader_status').hide();
                        jQuery('#preloader').hide();
                        
                    }
                });
              //window.location.href = url;
             }
            }
             c = true;
         });

  fileList.on('click', '.edit_fILE', function (e) {
            e.preventDefault();
                var id = jQuery(this).attr('id');
                jQuery("#file_id").val(id);
                jQuery("#editFile").show();
                c = true;
         });


    fileList.on('click', '.edit_folder', function (e) {
                            e.preventDefault();

            var id = jQuery(this).attr('id');
           var name = jQuery(this).attr('title');
           
                jQuery("#folder_id").val(id);
                jQuery("#editfolderName").val(name);
                jQuery("#folderEdit").show();
                c = true;
         });
*/



        fileList.on('click', 'div#folder_id_view', function (e) {
          
           if(c==false) {
            e.preventDefault();
            var nextDir = jQuery(this).find('a.folders').attr('href');
            
            var folder_id = jQuery(this).find('a.folders').attr('id');
            //console.log("id == ",folder_id);
            jQuery("#folder_iddd").val(folder_id);
            jQuery("#folder_parent_id").val(folder_id);
            if (filemanager.hasClass('searching')) {

                // Building the breadcrumbs

                breadcrumbsUrls = generateBreadcrumbs(nextDir);
                filemanager.removeClass('searching');
                filemanager.find('input[type=search]').val('').hide();
                filemanager.find('span').show();
            }
            else {
                breadcrumbsUrls.push(nextDir);
            }

            window.location.hash = encodeURIComponent(nextDir);
            currentPath = nextDir;
        }
        
        });
        // Clicking on breadcrumbs

        breadcrumbs.on('click', 'a', function (e) {
            e.preventDefault();
            var index = breadcrumbs.find('a').index(jQuery(this)),
                    nextDir = breadcrumbsUrls[index];
            // console.log("nextDir==",nextDir);
            var path = nextDir.split('/');
            demo = response;
            for (var i = 0; i < path.length; i++) {
                for (var j = 0; j < demo.length; j++) {

                    if (demo[j].name === path[i]) {
                        //console.log("currentPath", currentPath);
                        // var aa = currentPath.split("/");

                        var currn_fol = path[path.length - 1];
                        if (currn_fol == demo[j].name) {
                            console.log("currentfolder_id == ", demo[j].name + "==" + demo[j].id);
                            jQuery("#folder_iddd").val(demo[j].id);
                            jQuery("#folder_parent_id").val(demo[j].id);
                        }


                        demo = demo[j].items;
                        break;
                    }
                }
            }


            breadcrumbsUrls.length = Number(index);
            window.location.hash = encodeURIComponent(nextDir);
        });
        // Navigates to the given hash (path)

        function goto(hash) {

            hash = decodeURIComponent(hash).slice(1).split('=');
            
            if (hash.length) {
                var rendered = '';
                // if hash has search in it

                if (hash[0] === 'search') {

                    filemanager.addClass('searching');
                    rendered = searchData(response, hash[1].toLowerCase());
                    if (rendered.length) {
                        currentPath = hash[0];
                        render(rendered);
                    }
                    else {
                        render(rendered);
                    }

                }

                // if hash is some path

                else if (hash[0].trim().length) {
                    currentPath = hash[0];
                    var p = hash[0].trim();
                    path = p.split('/');
                    demo = response;
                    console.log("refresh path"+path);
                     for (var i = 0; i < path.length; i++) {
                for (var j = 0; j < demo.length; j++) {

                    if (demo[j].name === path[i]) {
                        console.log("currentPath refresh", currentPath);
                        var aa = currentPath.split("/");
                        var currn_fol = aa[aa.length - 1];
                        if (currn_fol == demo[j].name) {
                            jQuery("#folder_iddd").val(demo[j].id);
                            jQuery("#folder_parent_id").val(demo[j].id);
                        }
                        //console.log("currentfolder_id == ",demo[j].name+"=="+demo[j].id);
                        flag = 1;
                        demo = demo[j].items;
                        break;
                    }
                }
            }
                    rendered = searchByPath(hash[0]);
                    if (rendered.length) {

                        currentPath = hash[0];
                        breadcrumbsUrls = generateBreadcrumbs(hash[0]);
                        render(rendered);
                    }
                    else {
                        currentPath = hash[0];
                        breadcrumbsUrls = generateBreadcrumbs(hash[0]);
                        render(rendered);
                    }

                }

                // if there is no hash

                else {

                    currentPath = data.path;
                    breadcrumbsUrls.push(data.path);
                    render(searchByPath(data.path));
                }
            }
        }

        // Splits a file path and turns it into clickable breadcrumbs

        function generateBreadcrumbs(nextDir) {
            var path = nextDir.split('/').slice(0);
            for (var i = 1; i < path.length; i++) {
                path[i] = path[i - 1] + '/' + path[i];
            }
            return path;
        }


        // Locates a file by path

        function searchByPath(dir) {

            var path = dir.split('/'),
                    demo = response,
                    flag = 0;
            console.log("patssssh===", path);
            for (var i = 0; i < path.length; i++) {
                for (var j = 0; j < demo.length; j++) {

                    if (demo[j].name === path[i]) {
                        console.log("currentPath", currentPath);
                        var aa = currentPath.split("/");
                        var currn_fol = aa[aa.length - 1];
                        if (currn_fol == demo[j].name) {
                            jQuery("#folder_iddd").val(demo[j].id);
                            jQuery("#folder_parent_id").val(demo[j].id);
                        }
                        //console.log("currentfolder_id == ",demo[j].name+"=="+demo[j].id);
                        flag = 1;
                        demo = demo[j].items;
                        break;
                    }
                }
            }

            demo = flag ? demo : [];
            return demo;
        }


        // Recursively search through the file tree

        function searchData(data, searchTerms) {

            data.forEach(function (d) {
                if (d.type === 'folder') {

                    searchData(d.items, searchTerms);
                    if (d.name.toLowerCase().match(searchTerms)) {
                        folders.push(d);
                    }
                }
                else if (d.type === 'file') {
                    if (d.name.toLowerCase().match(searchTerms)) {
                        files.push(d);
                    }
                }
            });
            return {folders: folders, files: files};
        }


        // Render the HTML for the file manager

        function render(data) {
            var scannedFolders = [],
                    scannedFiles = [];
            if (Array.isArray(data)) {

                data.forEach(function (d) {

                    if (d.type === 'folder') {
                        scannedFolders.push(d);
                    }
                    else if (d.type === 'file') {
                        scannedFiles.push(d);
                    }

                });
            }
            else if (typeof data === 'object') {

                scannedFolders = data.folders;
                scannedFiles = data.files;
            }


            // Empty the old result and make the new one

            fileList.empty().hide();
            if (!scannedFolders.length && !scannedFiles.length) {
                filemanager.find('.nothingfound').show();
            }
            else {
                filemanager.find('.nothingfound').hide();
            }

            if (scannedFolders.length) {

                scannedFolders.forEach(function (f) {

                    var itemsLength = f.items.length,
                            name = escapeHTML(f.name),
                            icon = '<span class="icon folder"></span>';
                    if (itemsLength) {
                        icon = '<span class="icon folder full"></span>';
                    }

                    if (itemsLength == 1) {
                        itemsLength += ' item';
                    }
                    else if (itemsLength > 1) {
                        itemsLength += ' items';
                    }
                    else {
                        itemsLength = 'Empty';
                    }
                    var delUrl =ajaxurl+"?action=del&id="+f.id;
                    var editUrl =ajaxurl+"?action=edit&id="+f.id;
                    var folder = jQuery('<li class="folders" id = "fil_'+f.id+'"><div id="folder_id_view"><a id="' + f.id + '" href="' + f.path + '" title="' + f.path + '"  class="folders">' + icon + '<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a> </div><div class="deleteedit"><a  id="'+f.id+'"   class="del_folder">Delete</a>  <a onClick="editFolder(\''+f.id+'\',\''+f.name+'\')" id="'+f.id+'" title="' + f.name + '"  class="edit_folder" >Edit</a></div></li>');
                    folder.appendTo(fileList);
                });
            }

            if (scannedFiles.length) {

                scannedFiles.forEach(function (f) {

                    var fileSize = bytesToSize(f.size),
                            name = escapeHTML(f.name),
                            fileType = name.split('.'),
                            icon = '<span class="icon file"></span>';
                    fileType = fileType[fileType.length - 1];
                    icon = '<span class="icon file f-' + fileType + '">.' + fileType + '</span>';
                    
                    var file = jQuery('<li class="files" id = "fil_'+f.id+'"><a href="' + f.path + '" title="' + f.path + '" class="files">' + icon + '<span class="name">' + name + '</span> </a><div class="deleteedit"><a   id="'+f.id+'"  class="del_file">Delete</a>  <a id="'+f.id+'" onClick="editFile(\''+f.id+'\')"  class="edit_fILE" >Edit</a></div></li>');
                    file.appendTo(fileList);
                });
            }


            // Generate the breadcrumbs

            var url = '';
            if (filemanager.hasClass('searching')) {

                url = '<span>Search results: </span>';
                fileList.removeClass('animated');
            }
            else {

                fileList.addClass('animated');
                //console.log("breadcrumbs ",breadcrumbsUrls);
                breadcrumbsUrls.forEach(function (u, i) {

                    var name = u.split('/');
                    if (i !== breadcrumbsUrls.length - 1) {
                        url += '<a href="' + u + '"><span class="folderName">' + name[name.length - 1] + '</span></a> <span class="arrow-broad">→</span> ';
                    }
                    else {
                        url += '<span class="folderName">' + name[name.length - 1] + '</span>';
                    }

                });
            }

            breadcrumbs.text('').append(url);
            // Show the generated elements

            fileList.animate({'display': 'inline-block'});
        }


        // This function escapes special html characters in names

        function escapeHTML(text) {
            return text.replace(/\&/g, '&amp;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
        }

        // Convert file sizes from bytes to human readable units
        function bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0)
                return '0 Bytes';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        }

    });
}

jQuery("form#formId").submit(function (event) {

    //disable the default form submission
    event.preventDefault();
    //grab all form data  
    jQuery('#preloader_status').show();
    jQuery('#preloader').show();
    var formData = new FormData(jQuery(this)[0]);
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (returndata) {
            //alert(returndata);
              GetAllData(1);
            jQuery('#preloader_status').hide();
            jQuery('#preloader').hide();
            jQuery("#uploadfile").ezmodal('hide');
           
        }
    });
    return false;
});


jQuery("form#formId2").submit(function (event) {

    //disable the default form submission
    event.preventDefault();
    //grab all form data  
    jQuery('#preloader_status').show();
    jQuery('#preloader').show();
    var formData = new FormData(jQuery(this)[0]);
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (returndata) {
            //alert(returndata);

              GetAllData(1);
            jQuery('#preloader_status').hide();
            jQuery('#preloader').hide();
            jQuery("#folder_create").ezmodal('hide');
          
        }
    });
    return false;
});





jQuery("form#editFile1").submit(function (event) {

    //disable the default form submission
    event.preventDefault();
    //grab all form data  
    jQuery('#preloader_status').show();
    jQuery('#preloader').show();
    var formData = new FormData(jQuery(this)[0]);
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (returndata) {
            //alert(returndata);

              GetAllData(1);
            jQuery('#preloader_status').hide();
            jQuery('#preloader').hide();

            jQuery("#editFile").ezmodal('hide');
          
        }
    });
    return false;
});


jQuery("form#folderEdit1").submit(function (event) {

    //disable the default form submission
    event.preventDefault();
    //grab all form data  
    jQuery('#preloader_status').show();
    jQuery('#preloader').show();
    var formData = new FormData(jQuery(this)[0]);
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (returndata) {
            //alert(returndata);

              GetAllData(1);
            jQuery('#preloader_status').hide();
            jQuery('#preloader').hide();
            jQuery("#folderEdit").ezmodal('hide');
          
        }
    });
    return false;
});


jQuery( "a.del_folder" ).live( "click", function() {
 
  var id = jQuery(this).attr('id');
  var action="del_folder";
  var a = confirm("Are You Sure You want Delete this file!");    
   if(a === true)
     {
        jQuery('#preloader_status').show();
        jQuery('#preloader').show();
        jQuery.ajax({
            url: ajaxurl+"?action="+action+"&id="+id,
            type: 'GET',
         
            contentType: false,
            processData: false,
            success: function (returndata) {
                //alert(returndata);
                GetAllData(1);
                jQuery('#fil_'+id).hide();
                jQuery('#preloader_status').hide();
                jQuery('#preloader').hide();
                
            }
        });
      //window.location.href = url;
     }
});


jQuery( "a.del_file" ).live( "click", function() {
 
  var id = jQuery(this).attr('id');
  var action="del_file";
  var a = confirm("Are You Sure You want Delete this file!");    
   if(a === true)
     {
        jQuery('#preloader_status').show();
        jQuery('#preloader').show();
        jQuery.ajax({
            url: ajaxurl+"?action="+action+"&id="+id,
            type: 'GET',
         
            contentType: false,
            processData: false,
            success: function (returndata) {
                //alert(returndata);
                GetAllData(1);
                jQuery('#fil_'+id).hide();
                jQuery('#preloader_status').hide();
                jQuery('#preloader').hide();
                
            }
        });
      //window.location.href = url;
     }
});




});

function editFolder(id,title) {
 //var id = jQuery(this).attr('id');
          // var name = jQuery(this).attr('title');
                jQuery("#folder_id").val(id);
                jQuery("#editfolderName").val(title);
                jQuery("#folderEdit").show();
}

function editFile(id) {
        jQuery("#file_id").val(id);
        jQuery("#editFile").show();
}
/*
function deleteItem(ajaxurl,id,action)
{
         
    var a = confirm("Are You Sure You want Delete this file!");    
   if(a === true)
     {
        jQuery('#preloader_status').show();
        jQuery('#preloader').show();
        jQuery.ajax({
            url: ajaxurl+"?action="+action+"&id="+id,
            type: 'GET',
         
            contentType: false,
            processData: false,
            success: function (returndata) {
                //alert(returndata);
                //GetAllData(1);
                jQuery('#fil_'+id).hide();
                jQuery('#preloader_status').hide();
                jQuery('#preloader').hide();
                
            }
        });
      //window.location.href = url;
     }
            
}*/
