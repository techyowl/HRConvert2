<?php
$Alert = 'Cannot convert this file! Try changing the name.';
$Files = getFiles($ConvertTempDir);
$fileCount = count($Files);
$fcPlural1 = 's';
$fcPlural2 = 's are';
if (!is_numeric($fileCount)) $fileCount = 'an unknown number of';
if ($fileCount == 1) {
  $fcPlural1 = '';
  $fcPlural2 = ' is'; }
include ('header.php');
?>
  <body>
    <script type="text/javascript" src="Resources/jquery-3.3.1.min.js"></script>
    <div id="header-text" style="max-width:1000px; margin-left:auto; margin-right:auto; text-align:center;">
      <?php if (!isset($_GET['noGui'])) { ?><?php if(!empty($siteLogo)) { ?>
        <img src="<?php echo $siteLogo; ?>" />
        <?php } ?><h1><?php echo $siteTitle; ?></h1>
      <hr /><?php } ?>
      <h3>File Conversion Options</h3>
      <p>You have uploaded <?php echo $fileCount; ?> valid file<?php echo $fcPlural1; ?> to <?php echo $siteTitle; ?>.</p> 
      <p>Your file<?php echo $fcPlural2; ?> now ready to convert using the options below.</p>
    </div>

    <div align="center">
      <p><img id='loadingCommandDiv' name='loadingCommandDiv' src='Resources/pacman.gif' style="max-width:64px; max-height:64px; display:none;"/></p>
    </div>

    <div id="compressAll" name="compressAll" style="max-width:1000px; margin-left:auto; margin-right:auto; text-align:center;">
      <button id="backButton" name="backButton" style="width:50px;" class="info-button" onclick="window.history.back();">&#x2190;</button>
      <button id="refreshButton" name="refreshButton" style="width:50px;" class="info-button" onclick="javascript:location.reload(true);">&#x21BB;</button>
      <br /> <br />
      <button id="scandocMoreOptionsButton" name="scandocMoreOptionsButton" class="info-button" onclick="toggle_visibility('compressAllOptions');">Bulk File Options</button> 
      <div id="compressAllOptions" name="compressAllOptions" align="center" style="display:none;">
        <p>Compress & Download All Files:</p>
        <p>Specify Filename: <input type="text" id='userarchallfilename' name='userarchallfilename' value='HRConvert2_Files-<?php echo $Date; ?>'></p> 
        <select id='archallextension' name='archallextension'> 
          <option value="zip">Select Format</option>
          <option value="zip">Zip</option>
          <option value="rar">Rar</option>
          <option value="tar">Tar</option>
          <option value="7z">7z</option>
          <option value="tar.bz2">Tar.Bz2</option>
        </select>
        <input type="submit" id="archallSubmit" name="archallSubmit" class="info-button" value='Compress & Download' onclick="toggle_visibility('loadingCommandDiv');">
      
        <script type="text/javascript">
        $(document).ready(function () {
          $('#archallSubmit').click(function() {
            var archfiles = <?php echo json_encode($Files); ?>;
            var extension = $('#archallextension').val();
            if (extension === "") { 
              extension = 'zip'; } 
            $.ajax({
              type: "POST",
              url: 'index.php',
              data: {
                Token1:'<?php echo $Token1; ?>',
                Token2:'<?php echo $Token2; ?>',
                archive:'1',
                filesToArchive:archfiles,
                archextension:extension,
                userfilename:$('input[name="userarchallfilename"]').val() },
                success: function(ReturnData) {
                  $.ajax({
                  type: 'POST',
                  url: 'index.php',
                  data: { 
                    Token1:'<?php echo $Token1; ?>',
                    Token2:'<?php echo $Token2; ?>',
                    download:$('input[name="userarchallfilename"]').val()+'.'+extension},
                  success: function(returnFile) {
                    toggle_visibility('loadingCommandDiv');
                    window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userarchallfilename"]').val()+'.'+extension; }
                  }); },
                error: function(ReturnData) {
                  alert("<?php echo $Alert; ?>"); }
            });
          });
        });
        </script>

      </div>
    </div>
    <br />
    <div style="max-width:1000px; margin-left:auto; margin-right:auto;">
      <hr />

      <?php
      foreach ($Files as $File) {
        $extension = getExtension($ConvertTempDir.'/'.$File);
        $FileNoExt = str_replace($extension, '', $File);
        if (!in_array($extension, $convertArr)) continue;
        $ConvertGuiCounter1++;
      ?>

      <div id="file<?php echo $ConvertGuiCounter1; ?>" name="<?php echo $ConvertGuiCounter1; ?>">
        <a href="<?php echo 'DATA/'.$SesHash3.'/'.$File; ?>"><strong><?php echo $ConvertGuiCounter1; ?>.</strong> <u><?php echo $File; ?></u></a>
        
        <div id="buttonDiv<?php echo $ConvertGuiCounter1; ?>" name="buttonDiv<?php echo $ConvertGuiCounter1; ?>" style="height:25px;">
          <img id="archfileButton<?php echo $ConvertGuiCounter1; ?>" name="archfileButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/archive.png" style="float:left; display:block;" 
           onclick="toggle_visibility('archfileOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archfileButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archfileXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="archfileXButton<?php echo $ConvertGuiCounter1; ?>" name="archfileXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('archfileOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archfileButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archfileXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
         
          <?php if (in_array($extension, $pdfWorkArr)) { ?>          
          <a style="float:left;">&nbsp;|&nbsp;</a>
          
          <img id="docscanButton<?php echo $ConvertGuiCounter1; ?>" name="docscanButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/docscan.png" style="float:left; display:block;" 
           onclick="toggle_visibility('pdfOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('docscanButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('docscanXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="docscanXButton<?php echo $ConvertGuiCounter1; ?>" name="docscanXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('pdfOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('docscanButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('docscanXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $ArchiveArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="archiveButton<?php echo $ConvertGuiCounter1; ?>" name="archiveButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/convert.png" style="float:left; display:block;" 
           onclick="toggle_visibility('archiveOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archiveButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archiveXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="archiveXButton<?php echo $ConvertGuiCounter1; ?>" name="archiveXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('archiveOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archiveButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('archiveXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $DocumentArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="documentButton<?php echo $ConvertGuiCounter1; ?>" name="documentButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/document.png" style="float:left; display:block;" 
           onclick="toggle_visibility('docOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('documentButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('documentXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="documentXButton<?php echo $ConvertGuiCounter1; ?>" name="documentXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('docOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('documentButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('documentXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $SpreadsheetArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="spreadsheetButton<?php echo $ConvertGuiCounter1; ?>" name="spreadsheetButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/spreadsheet.png" style="float:left; display:block;" 
           onclick="toggle_visibility('spreadOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('spreadsheetButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('spreadsheetXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="spreadsheetXButton<?php echo $ConvertGuiCounter1; ?>" name="spreadsheetXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('spreadOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('spreadsheetButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('spreadsheetXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php }

          if (in_array($extension, $ImageArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="imageButton<?php echo $ConvertGuiCounter1; ?>" name="imageButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/photo.png" style="float:left; display:block;" 
           onclick="toggle_visibility('imageOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('imageButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('imageXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="imageXButton<?php echo $ConvertGuiCounter1; ?>" name="imageXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('imageOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('imageButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('imageXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php }

          if (in_array($extension, $MediaArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="mediaButton<?php echo $ConvertGuiCounter1; ?>" name="mediaButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/stream.png" style="float:left; display:block;" 
           onclick="toggle_visibility('audioOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('mediaButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('mediaXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="mediaXButton<?php echo $ConvertGuiCounter1; ?>" name="mediaXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('audioOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('mediaButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('mediaXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $VideoArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="videoButton<?php echo $ConvertGuiCounter1; ?>" name="videoButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/stream.png" style="float:left; display:block;" 
           onclick="toggle_visibility('videoOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('videoButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('videoXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="videoXButton<?php echo $ConvertGuiCounter1; ?>" name="videoXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('videoOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('videoButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('videoXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $DrawingArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="drawingButton<?php echo $ConvertGuiCounter1; ?>" name="drawingButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/convert.png" style="float:left; display:block;" 
           onclick="toggle_visibility('drawingOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('drawingButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('drawingXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="drawingXButton<?php echo $ConvertGuiCounter1; ?>" name="drawingXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('drawingOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('drawingButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('drawingXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } 

          if (in_array($extension, $ModelArray)) { ?>
          <a style="float:left;">&nbsp;|&nbsp;</a>

          <img id="modelButton<?php echo $ConvertGuiCounter1; ?>" name="modelButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/convert.png" style="float:left; display:block;" 
           onclick="toggle_visibility('modelOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('modelButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('modelXButton<?php echo $ConvertGuiCounter1; ?>');"/>
          <img id="modelXButton<?php echo $ConvertGuiCounter1; ?>" name="modelXButton<?php echo $ConvertGuiCounter1; ?>" src="Resources/x.png" style="float:left; display:none;" 
           onclick="toggle_visibility('modelOptionsDiv<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('modelButton<?php echo $ConvertGuiCounter1; ?>'); toggle_visibility('modelXButton<?php echo $ConvertGuiCounter1; ?>');"/> 
          <?php } ?>

          <a style="float:left;">&nbsp;|&nbsp;</a>

        </div>

        <div id='archfileOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='archfileOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Archive This File</p>
          <p>Specify Filename: <input type="text" id='userarchfilefilename<?php echo $ConvertGuiCounter1; ?>' name='userarchfilefilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='archfileextension<?php echo $ConvertGuiCounter1; ?>' name='archfileextension<?php echo $ConvertGuiCounter1; ?>'> 
            <option value="zip">Select Format</option>
            <option value="zip">Zip</option>
            <option value="rar">Rar</option>
            <option value="tar">Tar</option>
            <option value="7z">7z</option>
          </select></p>
          <input type="submit" id="archfileSubmit<?php echo $ConvertGuiCounter1; ?>" name="archfileSubmit<?php echo $ConvertGuiCounter1; ?>" value='Archive File' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#archfileSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  archive:'<?php echo $File; ?>',
                  filesToArchive:'<?php echo $File; ?>',
                  archextension:$('#archfileextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userfilename:$('input[name="userarchfilefilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userarchfilefilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#archfileextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userarchfilefilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#archfileextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php

        if (in_array($extension, $pdfWorkArr)) { 
        ?>
        <div id='pdfOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='pdfOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Perform Optical Character Recognition On This File</p>
          <p>Specify Filename: <input type="text" id='userpdffilename<?php echo $ConvertGuiCounter1; ?>' name='userpdffilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='pdfmethod<?php echo $ConvertGuiCounter1; ?>' name='pdfmethod<?php echo $ConvertGuiCounter1; ?>'>   
            <option value="0">Select Method</option>  
            <option value="1">Method 1 (Simple)</option>
            <option value="2">Method 2 (Advanced)</option>
          </select>
          <select id='pdfextension<?php echo $ConvertGuiCounter1; ?>' name='pdfextension<?php echo $ConvertGuiCounter1; ?>'>   
            <option value="pdf">Select Format</option> 
            <option value="pdf">Pdf</option>   
            <option value="doc">Doc</option>
            <option value="docx">Docx</option>
            <option value="rtf">Rtf</option>
            <option value="txt">Txt</option>
            <option value="odf">Odf</option>
          </select></p>
          <p><input type="submit" id='pdfconvertSubmit<?php echo $ConvertGuiCounter1; ?>' name='pdfconvertSubmit<?php echo $ConvertGuiCounter1; ?>' value='Convert Into Document' onclick="toggle_visibility('loadingCommandDiv');"></p>
          <script type="text/javascript">
          $(document).ready(function () {
            $('#pdfconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  pdfworkSelected:'<?php echo $File; ?>',
                  method1:$('#pdfmethod<?php echo $ConvertGuiCounter1; ?>').val(),
                  pdfextension:$('#pdfextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userpdfconvertfilename:$('input[name="userpdffilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userpdffilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#pdfextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userpdffilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#pdfextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $ArchiveArray)) {
        ?>
        <div id='archiveOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='archiveOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Archive</p>
          <p>Specify Filename: <input type="text" id='userarchivefilename<?php echo $ConvertGuiCounter1; ?>' name='userarchivefilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='archiveextension<?php echo $ConvertGuiCounter1; ?>' name='archiveextension<?php echo $ConvertGuiCounter1; ?>'> 
            <option value="zip">Select Format</option>
            <option value="zip">Zip</option>
            <option value="rar">Rar</option>
            <option value="tar">Tar</option>
            <option value="7z">7z</option>
          </select></p>
          <input type="submit" id="archiveconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="archiveconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Archive Files' onclick="toggle_visibility('loadingCommandDiv'); display:none;">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#archiveconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#archiveextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userarchivefilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userarchivefilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#archiveextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userarchivefilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#archiveextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $DocumentArray)) {
        ?>
        <div id='docOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='docOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Document</p>
          <p>Specify Filename: <input type="text" id='userdocfilename<?php echo $ConvertGuiCounter1; ?>' name='userdocfilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='docextension<?php echo $ConvertGuiCounter1; ?>' name='docextension<?php echo $ConvertGuiCounter1; ?>'> 
            <option value="txt">Select Format</option>
            <option value="doc">Doc</option>
            <option value="docx">Docx</option>
            <option value="rtf">Rtf</option>
            <option value="txt">Txt</option>
            <option value="odf">Odf</option>
            <option value="pdf">Pdf</option>
          </select></p>
          <input type="submit" id="docconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="docconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Document' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#docconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#docextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userdocfilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userdocfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#docextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userdocfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#docextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php }

        if (in_array($extension, $SpreadsheetArray)) {
        ?>
        <div id='spreadOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='spreadOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Spreadsheet</p>
          <p>Specify Filename: <input type="text" id='userspreadfilename<?php echo $ConvertGuiCounter1; ?>' name='userspreadfilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='spreadextension<?php echo $ConvertGuiCounter1; ?>' name='spreadextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="ods">Select Format</option> 
            <option value="xls">Xls</option>
            <option value="xlsx">Xlsx</option>
            <option value="ods">Ods</option>
            <option value="pdf">Pdf</option>
          </select></p>
          <input type="submit" id="spreadconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="spreadconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Spreadsheet' onclick="toggle_visibility('loadingCommandDiv');">        
          <script type="text/javascript">
          $(document).ready(function () {
            $('#spreadconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#spreadextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userspreadfilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userspreadfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#spreadextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userspreadfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#spreadextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php }

        if (in_array($extension, $PresentationArray)) {
        ?>
        <div id='presentationOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='presentationOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Presentation</p>
          <p>Specify Filename: <input type="text" id='userpresentationfilename<?php echo $ConvertGuiCounter1; ?>' name='userpresentationfilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='presentationextension<?php echo $ConvertGuiCounter1; ?>' name='presentationextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="odp">Select Format</option>
            <option value="pages">Pages</option>
            <option value="pptx">Pptx</option>
            <option value="ppt">Ppt</option>
            <option value="xps">Xps</option>
            <option value="potx">Potx</option>
            <option value="pot">Pot</option>
            <option value="ppa">Ppa</option>
            <option value="odp">Odp</option>
          </select></p>
          <input type="submit" id="presentationconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="presentationconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Presentation' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#presentationconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#presentationextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userpresentationfilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userphotofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#presentationextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userpresentationfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#presentationextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $MediaArray)) {
        ?>
        <div id='audioOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='audioOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Audio</p>
          <p>Specify Filename: <input type="text" id='useraudiofilename<?php echo $ConvertGuiCounter1; ?>' name='useraudiofilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='audioextension<?php echo $ConvertGuiCounter1; ?>' name='audioextension<?php echo $ConvertGuiCounter1; ?>'> 
            <option value="mp3">Select Format</option>
            <option value="mp2">Mp2</option>  
            <option value="mp3">Mp3</option>
            <option value="wav">Wav</option>
            <option value="wma">Wma</option>
            <option value="flac">Flac</option>
            <option value="ogg">Ogg</option>
          </select></p>
          <input type="submit" id="audioconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="audioconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Audio' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#audioconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#audioextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="useraudiofilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="useraudiofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#audioextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="useraudiofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#audioextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $VideoArray)) {
        ?>
        <div id='videoOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='videoOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Video</p>
          <p>Specify Filename: <input type="text" id='uservideofilename<?php echo $ConvertGuiCounter1; ?>' name='uservideofilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='videoextension<?php echo $ConvertGuiCounter1; ?>' name='videoextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="mp4">Select Format</option> 
            <option value="3gp">3gp</option> 
            <option value="mkv">Mkv</option> 
            <option value="avi">Avi</option>
            <option value="mp4">Mp4</option>
            <option value="flv">Flv</option>
            <option value="mpeg">Mpeg</option>
            <option value="wmv">Wmv</option>
            <option value="mov">Mov</option>
            <option value="gif">Gif</option>
          </select></p>
          <input type="submit" id="videoconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="videoconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Video' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#videoconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#videoextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="uservideofilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="uservideofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#videoextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="uservideofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#videoextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $ModelArray)) {
        ?>
        <div id='modelOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='modelOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This 3D Model</p>
          <p>Specify Filename: <input type="text" id='usermodelfilename<?php echo $ConvertGuiCounter1; ?>' name='usermodelfilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='modelextension<?php echo $ConvertGuiCounter1; ?>' name='modelextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="3ds">Select Format</option>
            <option value="3ds">3ds</option>
            <option value="collada">Collada</option>
            <option value="obj">Obj</option>
            <option value="off">Off</option>
            <option value="ply">Ply</option>
            <option value="stl">Stl</option>
            <option value="ptx">Ptx</option>
            <option value="dxf">Dxf</option>
            <option value="u3d">U3d</option>
            <option value="vrml">Vrml</option>
          </select></p>
          <input type="submit" id="modelconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="modelconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Model' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#modelconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#modelextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="usermodelfilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="usermodelfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#modelextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="usermodelfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#modelextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $DrawingArray)) {
        ?>
        <div id='drawingOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='drawingOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Technical Drawing Or Vector File</p>
          <p>Specify Filename: <input type="text" id='userdrawingfilename<?php echo $ConvertGuiCounter1; ?>' name='userdrawingfilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='drawingextension<?php echo $ConvertGuiCounter1; ?>' name='drawingextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="jpg">Select Format</option>
            <option value="svg">Svg</option>
            <option value="dxf">Dxf</option>
            <option value="vdx">Vdx</option>
            <option value="fig">Fig</option>
            <option value="jpg">Jpg</option>
            <option value="png">Png</option>
            <option value="bmp">Bmp</option>
            <option value="pdf">Pdf</option>
          </select></p>
          <input type="submit" id="drawingconvertSubmit<?php echo $ConvertGuiCounter1; ?>" name="drawingconvertSubmit<?php echo $ConvertGuiCounter1; ?>" value='Convert Drawing' onclick="toggle_visibility('loadingCommandDiv');">     
          <script type="text/javascript">
          $(document).ready(function () {
            $('#drawingconvertSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  extension:$('#drawingextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userdrawingfilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="drawingfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#drawingextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userdrawingfilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#drawingextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        </div>
        <?php } 

        if (in_array($extension, $ImageArray1)) {
        ?>
        <div id='imageOptionsDiv<?php echo $ConvertGuiCounter1; ?>' name='imageOptionsDiv<?php echo $ConvertGuiCounter1; ?>' style="max-width:750px; display:none;">
          <p style="max-width:1000px;"></p>
          <p>Convert This Image</p>
          <p>Specify Filename: <input type="text" id='userphotofilename<?php echo $ConvertGuiCounter1; ?>' name='userphotofilename<?php echo $ConvertGuiCounter1; ?>' value='<?php echo str_replace('.', '', $FileNoExt); ?>'>
          <select id='photoextension<?php echo $ConvertGuiCounter1; ?>' name='photoextension<?php echo $ConvertGuiCounter1; ?>'>
            <option value="jpg">Select Format</option>
            <option value="jpg">Jpg</option>
            <option value="bmp">Bmp</option>
            <option value="png">Png</option>
          </select></p>
          <p>Width and height: </p>
          <p><input type="number" size="4" value="0" id='width<?php echo $ConvertGuiCounter1; ?>' name='width<?php echo $ConvertGuiCounter1; ?>' min="0" max="10000"> X <input type="number" size="4" value="0" id="height<?php echo $ConvertGuiCounter1; ?>" name="height<?php echo $ConvertGuiCounter1; ?>" min="0"  max="10000"></p> 
          <p>Rotate: <input type="number" size="3" id='rotate<?php echo $ConvertGuiCounter1; ?>' name='rotate<?php echo $ConvertGuiCounter1; ?>' value="0" min="0" max="359"></p>
          <input type="submit" id='convertPhotoSubmit<?php echo $ConvertGuiCounter1; ?>' name='convertPhotoSubmit<?php echo $ConvertGuiCounter1; ?>' value='Convert Image' onclick="toggle_visibility('loadingCommandDiv');">
          <script type="text/javascript">
          $(document).ready(function () {
            $('#convertPhotoSubmit<?php echo $ConvertGuiCounter1; ?>').click(function() {
              $.ajax({
                type: "POST",
                url: 'index.php',
                data: {
                  Token1:'<?php echo $Token1; ?>',
                  Token2:'<?php echo $Token2; ?>',
                  convertSelected:'<?php echo $File; ?>',
                  rotate:$('#rotate<?php echo $ConvertGuiCounter1; ?>').val(),
                  width:$('#width<?php echo $ConvertGuiCounter1; ?>').val(),
                  height:$('#height<?php echo $ConvertGuiCounter1; ?>').val(),
                  extension:$('#photoextension<?php echo $ConvertGuiCounter1; ?>').val(),
                  userconvertfilename:$('input[name="userphotofilename<?php echo $ConvertGuiCounter1; ?>"]').val() },
                  success: function(ReturnData) {
                    $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: { 
                      Token1:'<?php echo $Token1; ?>',
                      Token2:'<?php echo $Token2; ?>',
                      download:$('input[name="userphotofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#photoextension<?php echo $ConvertGuiCounter1; ?>').val()},
                    success: function(returnFile) {
                      toggle_visibility('loadingCommandDiv');
                      window.location.href = "<?php echo 'DATA/'.$SesHash3.'/'; ?>"+$('input[name="userphotofilename<?php echo $ConvertGuiCounter1; ?>"]').val()+'.'+$('#photoextension<?php echo $ConvertGuiCounter1; ?>').val(); }
                    }); },
                  error: function(ReturnData) {
                    alert("<?php echo $Alert; ?>"); }
              });
            });
          });
          </script>
        <?php } ?>
      </div>
      <hr />
      <?php } ?>
    </div>

    <?php
    include ('footer.php');
    ?>