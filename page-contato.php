<?php

  //response generation function

  $response = "";

  //function to generate response
  function my_contact_form_generate_response($type, $message){

    global $response;

    if($type == "success") $response = "<div class='success'>{$message}</div>";
    else $response = "<div class='button'>{$message}</div>";
  }

  //Placeholder
  $ph_name = "NOME";
  $ph_email = "E-MAIL";
  $ph_message = "MENSAGEM";
  $ph_human = "";

  //response messages
  $not_human       = "Verificação humana incorreta.";
  $missing_content = "Por favor coloque todas as informações.";
  $email_invalid   = "Email inválido.";
  $message_unsent  = "Mensagem não enviada. Tente de novo.";
  $message_sent    = "Obrigado! Sua mensagem foi enviada.";

  //user posted variables
  $name = $_POST['message_name'];
  $email = $_POST['message_email'];
  $message = $_POST['message_text'];
  $human = $_POST['message_human'];
  $machine = $_POST['message_machine'];

  //php mailer variables
  $to = get_option('admin_email');
  $subject = "Email enviado do site da ".get_bloginfo('name');
  $headers = 'From: '. $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n";

  if(!$human == 0){
    if($human != $machine) my_contact_form_generate_response("error", $not_human); //not human!
    else {

      //validate email
      if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        my_contact_form_generate_response("error", $email_invalid);
      else //email is valid
      {
        //validate presence of name and message
        if(empty($name) || empty($message)){
          my_contact_form_generate_response("error", $missing_content);
        }
        else //ready to go!
        {
          $sent = wp_mail($to, $subject, strip_tags($message), $headers);
          if($sent) my_contact_form_generate_response("success", $message_sent); //message sent!
          else my_contact_form_generate_response("error", $message_unsent); //message wasn't sent
        }
      }
    }
  }
  else if ($_POST['submitted']) my_contact_form_generate_response("error", $missing_content);

get_header(); ?>
<div class = "main" style = "background-color: #dbdbd8">

  <div class = "diferenca"></div>

	<div style = "padding: 50px 4%">
		<h1 class = "titulo">Entre em contato</h1>
		
    <div id = "respond">
      
      <form action = "<?php the_permalink(); ?>" method = "post" style = "padding-top: 30px">
        <input class = "col-xs-12" type = "text" name = "message_name" value = "<?php echo esc_attr($_POST['message_name']); ?>" placeholder = "<?php echo $ph_name; ?>" />
        <input class = "col-xs-12" type = "text" name = "message_email" value = "<?php echo esc_attr($_POST['message_email']); ?>" placeholder = "<?php echo $ph_email; ?>" />
        <textarea class = "col-xs-12" type = "text" name = "message_text" rows = "5" placeholder = "<?php echo $ph_message; ?>"><?php echo esc_textarea($_POST['message_text']); ?></textarea>
        <p style = "padding-left: 0; display: inline-block; text-transform: uppercase">digite este número <spam style = "margin: 0 5px; padding: 4px 5px; border: solid 1px black"><?php $Random_code=rand(10,100); echo$Random_code; ?></spam> para enviar</p>
        <label style = "padding-left: 20px"><input type = "text" style = "width: 20px" name="message_human" placeholder = "<?php echo $ph_human; ?>" /></label>
        <input type = "hidden" name = "message_machine" value = "<?php echo $Random_code; ?>" />
        <input type = "hidden" name = "submitted" value = "1">
        <button type = "submit" name = "send" style = "float: right; text-transform: uppercase">enviar</button>
      </form>

      <?php echo $response; ?>

    </div>
	</div>


</div>

<?php get_footer(); ?>