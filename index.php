<?php
	require_once('utils.php');
	
	$ranking = isset($_GET['ranking']) ? $_GET['ranking'] : '';
	$count = isset($_GET['count']) ? $_GET['count'] : '';
	$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

	if ($ranking && $count && $sort) {
		$stories_id = get_stories_id($ranking);
		$stories_id = array_slice($stories_id, 0, $count);
		$stories = get_items($stories_id);

		sort_stories($sort, $stories);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Test PHP</title>
		<style>
			* {
				box-sizing: border-box;
			}

			body {
				padding: 2rem;
			}

			body > * + * {
				margin-top: 2rem;
			}

			fieldset {
				display: inline-flex;
				align-items: flex-end;
				gap: 2rem;
				padding: 1rem;
				padding-top: 0.5rem;
			}

			fieldset > div {
				display: flex;
				flex-direction: column;
				gap: 1rem;
			}

			td,
			th {
				padding: 0.5rem;
				text-align: left;
			}
		</style>
	</head>
	<body>
		<form>
			<fieldset>
				<legend>Filtres</legend>
				<div>
					<label for="ranking">Classement</label>
					<select name="ranking" id="ranking">
						<option value="new">New</option>
						<option value="top">Top</option>
						<option value="best">Best</option>
					</select>
				</div>
				<div>
					<label for="count">Nombre de résultats</label>
					<select name="count" id="count">
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
					</select>
				</div>
				<div>
					<label for="sort">Trier par</label>
					<select name="sort" id="sort">
						<option value="a-z">A → Z</option>
						<option value="z-a">Z → A</option>
						<option value="score">Score</option>
					</select>
				</div>
				<div>
					<button type="submit">Filter</button>
				</div>
			</fieldset>
		</form>

		<!-- Tableau de résultats -->
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Titre</th>
					<th>Auteur</th>
					<th>URL</th>
					<th>Score</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>
						<a
							href="https://news.ycombinator.com/item?id=1"
							target="_blank"
							rel="noopener noreferer"
							>Ceci est un test</a
						>
					</td>
					<td>T. Mathis</td>
					<td>
						<a
							href="https://www.studiometa.fr"
							target="_blank"
							rel="noopener noreferer"
							>https://www.studiometa.fr</a
						>
					</td>
					<td>10</td>
				</tr>
				<tr>
					<td>...</td>
					<td>...</td>
					<td>...</td>
					<td>...</td>
					<td>...</td>
				</tr>
				<?php if (isset($stories)) print_stories($stories); ?>
			</tbody>
		</table>
	</body>
</html>
