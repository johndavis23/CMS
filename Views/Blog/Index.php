                    <?php
                        $this->render("Local/Header", ["title"=> "Archived Blog Posts"]);
                    ?>
                    <ul>
                        <?php
                        foreach ($posts as $post) {
                            if ($post['post_title']) {
                                echo '<li><a href="/pestblog/Barebones-WP/index.php/Blog/View/', $post['ID'], '">',
                                $post['post_title'],
                                    "</a> - ".$post['post_type']."</li>";
                            }

                        }
                        ?>
                    </ul>

                    <?php
                    $this->render("Local/Footer", []);
                    ?>