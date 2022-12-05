<?php
session_start();

if(isset($_POST["clear_session"])) {
	session_destroy();
	header("Location: /templi");
	exit;
}

if(isset($_POST["submit"])) {
	$data = $_SESSION["data"] = htmlspecialchars($_POST["data"]);
	$template = $_SESSION["template"] = htmlspecialchars($_POST["template"]);

	$slug = preg_replace('/[^a-z0-9-]/', '-',  strtolower($data));
	$slug = preg_replace('/-+/', "-", $slug);
	$slug = trim($slug, "-");
	
	$result = str_replace("{{slug}}", $slug, $template);
	$result = str_replace("{{data}}", $data, $result);
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<script src="https://cdn.tailwindcss.com"></script>
		<title>Templi</title>
		<style>
			@import url("https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700&display=swap");
			* {
				border: 0;
				margin: 0;
				padding: 0;
				box-sizing: border-box;
				transition: all 0.3s ease;
			}
			html, body {
				font-family: "Poppins", sans-serif;
				font-weight: 400;
				font-size: 14px;
				line-height: 1.5;
			}
		</style>
		
		<script type="text/javascript">
			function copyResult(data) {
				(async () => {
					try {
						await navigator.clipboard.writeText(data);
						const toast = document.getElementById("toast");
						toast.style.display = "block";
						setTimeout(function() {
							toast.style.display = "none";
						}, 3000);
					} catch (err) {
						console.error('Failed to copy: ', err);
					}
				})();
			}
		</script>
	</head>
	<body class="antialiased">
		<div id="toast" class="fixed top-2 left-2 bg-neutral-900 text-xs text-white py-4 px-6 z-10" style="display: none;">Copied to clipboard!</div>
		<main class="min-h-screen min-w-screen flex flex-col lg:flex-row-reverse gap-8 items-center justify-center">
			
			<?php if(isset($result)): ?>
				<div class="w-full lg:w-2/5 bg-neutral-100 p-8 mb-6">
					<p id="result"><?= $result ?></p>
					<div class="flex justify-end mt-8">
						<button type="button" onclick="copyResult('<?= $result ?>');" class="border border-neutral-900 hover:bg-neutral-900 hover:text-white py-4 px-8">Copy</button>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="lg:w-3/6 2xl:w-2/6 3xl:w-1/5">
				<form method="POST" action="" class="border border-neutral-900 px-8 py-10">
					<h1 class="text-5xl font-bold mb-8">Templi</h1>
					
					<label for="data"></label>
					<input type="text" name="data" id="data" placeholder="Enter data to inject into template and/or slugify" value="<?= $_SESSION["data"] ?? '' ?>" class="w-full block bg-neutral-100 text-neutral-900 p-4" required />
					
					<label for="template"></label>
					<textarea name="template" id="template" placeholder="Enter template to inject data into" class="w-full block bg-neutral-100 text-neutral-900 resize-none p-4 mt-4" rows="10" required><?= $_SESSION["template"] ?? '' ?></textarea>
					
					<div class="space-x-4">
						<button type="submit" class="text-white bg-neutral-900 hover:bg-neutral-600 py-4 px-8 mt-4" name="submit">Submit</button>
						<button type="submit" class="text-neutral-900 bg-neutral-200 hover:bg-neutral-300 py-4 px-8 mt-4" name="clear_session">Clear Session</button>
					</div>
				</form>
			</div>
		</main>
	</body>
</html>
