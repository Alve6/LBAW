-- Generated with ChatGPT and light editions for mistakes. 
-- The full prompts and answers can be seen here: 
-- https://chatgpt.com/share/6907cf39-5780-8013-9c86-48fac23d1b00

SET search_path TO lbaw2511;

-- Insert Users (10 users)
INSERT INTO users (username, name, email, profile_image, password, description) VALUES
  ('alice', 'Alice Anderson', 'alice@example.com', NULL,'$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'Loves news'),
  ('bob', 'Bob Brown', 'bob@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'Reporter'),
  ('carol', 'Carol Clark', 'carol@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'Commenter'),
  ('dave', 'Dave Davis', 'dave@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'Moderator candidate'),
  ('eve', 'Eve Evans', 'eve@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'Admin candidate'),
  ('frank', 'Frank Foster', 'frank@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'General user'),
  ('gina', 'Gina Green', 'gina@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'General user'),
  ('heidi', 'Heidi Hall', 'heidi@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'General user'),
  ('ivan', 'Ivan Ivy', 'ivan@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'General user'),
  ('judy', 'Judy Jones', 'judy@example.com', NULL, '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'General user'),
  ('admin', 'Admin John', 'admin@admin.com', 'crMCiH5HpiHgtyAwGOdvdd3pjT7WWstNCsgG3tkb.jpg', '$2y$12$JrVXmJHp2hDVtwWzUM2oBubUmZl0Ew0XEUvdiHGdW8hTaUVOUZF1G', 'NoMisHub main administrator'),
  ('regUser', 'John Snow', 'reg@user.com', 'z07jHvZFJ8eM22jqLsICICXLsfQjgZ3f3XiDOL2v.jpg', '$2y$12$4Xp9NNog0GXgemuhIkpHqutt.T8DoF1pwRpRbH7Ra5H6PB41WZMGG', 'I''m John Snow, and I''m just a regular user.');

-- Choose some admins and moderators (distinct sets)
INSERT INTO admins (user_id) VALUES
  ((SELECT id FROM users WHERE username = 'eve')),
  ((SELECT id FROM users WHERE username = 'alice')),
  ((SELECT id FROM users WHERE username = 'admin'));

INSERT INTO moderators (user_id) VALUES
  ((SELECT id FROM users WHERE username = 'dave')),
  ((SELECT id FROM users WHERE username = 'bob'));

-- Insert categories (some sample)
INSERT INTO categories (name) VALUES
  ('Technology'),
  ('Politics'),
  ('Sports'),
  ('Health'),
  ('Entertainment');

-- Insert news items (at least 5)
INSERT INTO news (title, content, date, user_id) VALUES
  ('Tech Trends 2025', '2025 is shaping up to be a landmark year in technology, with AI, quantum computing, and automation leading the charge. Businesses across industries are increasingly adopting AI-powered tools to streamline workflows, reduce costs, and enhance customer experiences. Quantum computing research is moving from theory to practical applications, promising breakthroughs in cryptography, material science, and drug discovery. Meanwhile, sustainable energy solutions for data centers are gaining traction as companies aim to reduce carbon footprints. Virtual reality (VR) and augmented reality (AR) are also becoming mainstream, impacting education, healthcare, and entertainment. Experts predict that staying ahead of these innovations will be crucial for businesses and individuals aiming to thrive in an increasingly digital and competitive world.', now() - interval '10 days', (SELECT id FROM users WHERE username='bob')),
  ('Election Highlights', 'The recent national election captured attention with historic voter turnout and unexpected outcomes. Key battleground regions determined critical seats, reflecting shifting demographics and voter priorities. Debates over healthcare reform, climate policy, economic recovery, and education dominated discussions, illustrating the concerns of a diverse electorate. Political analysts emphasize the importance of civic engagement and informed decision-making to strengthen democratic processes. The results signal potential changes in legislative priorities and policy implementation over the coming years. As new leaders take office, bridging political divides, maintaining transparency, and addressing pressing national challenges will be essential for sustaining public trust and fostering a stable, forward-looking political environment.', now() - interval '5 days', (SELECT id FROM users WHERE username='alice')),
  ('Health Breakthrough', 'Medical researchers have announced a groundbreaking discovery with the potential to revolutionize patient care worldwide. Innovative therapies targeting previously untreatable conditions are showing remarkable success in early clinical trials, offering hope to millions. Advances in genetic research, biotechnology, and precision medicine are enabling highly personalized treatments, improving recovery rates while minimizing side effects. Experts note that these breakthroughs could transform approaches to chronic diseases, cancer, and rare genetic disorders. Public health advocates stress the importance of continued investment in scientific research, infrastructure, and education to ensure these innovations are accessible and affordable. This milestone highlights the power of modern science to improve health outcomes and extend human longevity.', now() - interval '8 days', (SELECT id FROM users WHERE username='alice')),
  ('Sports Finals', 'The championship finals delivered an unforgettable display of talent, strategy, and resilience. Teams battled intensely throughout the season, culminating in a final match that thrilled fans around the globe. Star athletes showcased extraordinary performances, breaking records and demonstrating the results of years of dedication and training. Beyond individual achievements, the games highlighted teamwork, sportsmanship, and tactical brilliance, emphasizing the importance of preparation and collaboration. Fans celebrated every goal, point, and play while discussing key moments online and offline. The finals not only crowned champions but also reminded audiences of the power of sports to unite communities, inspire young athletes, and create shared cultural experiences that extend far beyond the stadium.', now() - interval '3 days', (SELECT id FROM users WHERE username='dave')),
  ('Entertainment Buzz', 'This week in entertainment, audiences are captivated by blockbuster films, exciting music releases, and rising stars making their mark in film, television, and streaming platforms. Filmmakers continue to push creative boundaries, combining stunning visuals, immersive sound design, and innovative storytelling to attract global audiences. Streaming services are changing how viewers consume content, offering personalized experiences and bringing international productions to new markets. Music, gaming, and live performances are also seeing rapid growth, with fans eagerly following trends, reviews, and behind-the-scenes developments. As the entertainment industry evolves, it continues to blend technology and creativity, shaping cultural conversations, influencing fashion and lifestyle, and providing millions worldwide with memorable and inspiring experiences.', now() - interval '2 days', (SELECT id FROM users WHERE username='gina'));

-- Link news to categories
INSERT INTO news_category (news_id, category_id) VALUES
  (1, 'Technology'),
  (2, 'Politics'),
  (3, 'Health'),
  (4, 'Sports'),
  (5, 'Entertainment'),
  (5, 'Technology');  -- can be in multiple categories

-- Insert comments (at least 10 comments)
INSERT INTO comments (content, date, user_id, news_id) VALUES
  ('Great article!', now() - interval '9 days', (SELECT id FROM users WHERE username='carol'), 1),
  ('I disagree with this point.', now() - interval '4 days', (SELECT id FROM users WHERE username='frank'), 2),
  ('Can you clarify?', now() - interval '7 days', (SELECT id FROM users WHERE username='gina'), 1),
  ('Interesting take.', now() - interval '2 days', (SELECT id FROM users WHERE username='ivan'), 4),
  ('Thanks for sharing!', now() - interval '1 day', (SELECT id FROM users WHERE username='judy'), 5),
  ('Needs more sources.', now() - interval '3 days', (SELECT id FROM users WHERE username='heidi'), 2),
  ('Well written.', now() - interval '6 days', (SELECT id FROM users WHERE username='bob'), 3),
  ('I have a question.', now() - interval '1 days', (SELECT id FROM users WHERE username='alice'), 4),
  ('Good read.', now() - interval '5 hours', (SELECT id FROM users WHERE username='carol'), 5),
  ('More details please.', now() - interval '2 days', (SELECT id FROM users WHERE username='frank'), 3);

-- Insert votes (for news and comments)
-- First create general vote records
INSERT INTO votes (user_id, value, date) VALUES
  ((SELECT id FROM users WHERE username='alice'), 1, now() - interval '9 days'),
  ((SELECT id FROM users WHERE username='bob'), 1, now() - interval '8 days'),
  ((SELECT id FROM users WHERE username='carol'), -1, now() - interval '7 days'),
  ((SELECT id FROM users WHERE username='dave'), 1, now() - interval '6 days'),
  ((SELECT id FROM users WHERE username='eve'), 1, now() - interval '5 days'),
  ((SELECT id FROM users WHERE username='frank'), -1, now() - interval '4 days'),
  ((SELECT id FROM users WHERE username='gina'), 1, now() - interval '3 days'),
  ((SELECT id FROM users WHERE username='heidi'), 1, now() - interval '2 days'),
  ((SELECT id FROM users WHERE username='ivan'), -1, now() - interval '1 day'),
  ((SELECT id FROM users WHERE username='judy'), 1, now());

-- Now choose some of those votes to map to news or comment
-- Suppose vote IDs auto‐assigned 1..10 in same order
INSERT INTO news_votes (vote_id, news_id, user_id) VALUES
  (1, 1, (SELECT id FROM users WHERE username='alice')),
  (2, 2, (SELECT id FROM users WHERE username='bob')),
  (4, 4, (SELECT id FROM users WHERE username='dave')),
  (5, 5, (SELECT id FROM users WHERE username='eve')),
  (7, 5, (SELECT id FROM users WHERE username='gina')),
  (8, 1, (SELECT id FROM users WHERE username='heidi')),
  (10, 3, (SELECT id FROM users WHERE username='judy'));

INSERT INTO comment_votes (vote_id, comment_id, user_id) VALUES
  (3, 1, (SELECT id FROM users WHERE username='carol')),
  (6, 2, (SELECT id FROM users WHERE username='heidi')),
  (9, 3, (SELECT id FROM users WHERE username='ivan'));

-- Insert follows
INSERT INTO follows (follower_id, followed_id) VALUES
  ((SELECT id FROM users WHERE username='carol'), (SELECT id FROM users WHERE username='alice')),
  ((SELECT id FROM users WHERE username='frank'), (SELECT id FROM users WHERE username='bob')),
  ((SELECT id FROM users WHERE username='gina'), (SELECT id FROM users WHERE username='alice'));

-- Create notifications and notification links
-- E.g. comment notifications
-- For each comment by someone to someone else, generate a notification
-- Example: when carol comments on Alice’s news, notify Alice
INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='alice'), FALSE, now() - interval '9 days'),
  ((SELECT id FROM users WHERE username='alice'), FALSE, now() - interval '7 days'),
  ((SELECT id FROM users WHERE username='bob'), FALSE, now() - interval '4 days');

INSERT INTO comment_notifications (notification_id, comment_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='alice') ORDER BY id LIMIT 1), 1),
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='alice') ORDER BY id OFFSET 1 LIMIT 1), 3),
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='bob') ORDER BY id LIMIT 1), 2);

-- Vote notifications (when someone votes your news/comment)
INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='bob'), FALSE, now() - interval '8 days'),
  ((SELECT id FROM users WHERE username='dave'), FALSE, now() - interval '5 days');

INSERT INTO vote_notifications (notification_id, vote_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='bob') ORDER BY id LIMIT 1), 2),
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='dave') ORDER BY id LIMIT 1), 4);

-- Follow notification
INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='alice'), FALSE, now() - interval '3 days');

INSERT INTO follow_notifications (notification_id, follow_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='alice') ORDER BY id LIMIT 1),
   (SELECT id FROM follows WHERE follower_id = (SELECT id FROM users WHERE username='carol') AND followed_id = (SELECT id FROM users WHERE username='alice')));

-- Reports & report notifications
INSERT INTO reports (content, date, user_id) VALUES
  ('Inappropriate behavior', now() - interval '2 days', (SELECT id FROM users WHERE username='frank')),
  ('Spam content', now() - interval '1 day', (SELECT id FROM users WHERE username='gina'));

INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='frank'), FALSE, now() - interval '1 day'),
  ((SELECT id FROM users WHERE username='gina'), FALSE, now());

INSERT INTO report_notifications (notification_id, report_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='frank') ORDER BY id LIMIT 1), 1),
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='gina') ORDER BY id LIMIT 1), 2);

-- For report_notification_admin: assign admin(s) to reports
INSERT INTO report_notifications_admins (report_notification_id, admin_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='frank') ORDER BY id LIMIT 1), (SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='eve'))),
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='gina') ORDER BY id LIMIT 1), (SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='alice')));

-- Admin actions (at least 3)
INSERT INTO admin_actions (admin_id, date) VALUES
  ((SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='eve')), now() - interval '1 day'),
  ((SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='alice')), now() - interval '2 days'),
  ((SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='eve')), now());

-- Admin_news_action, admin_user_action, promotion, admin_action_notification
INSERT INTO admin_news_actions (admin_action_id, news_id, reason) VALUES
  ((SELECT id FROM admin_actions ORDER BY id LIMIT 1), 2, 'Inaccurate headline'),
  ((SELECT id FROM admin_actions ORDER BY id OFFSET 1 LIMIT 1), 3, 'Violation of policy');

INSERT INTO admin_user_actions (admin_action_id, user_id, reason) VALUES
  ((SELECT id FROM admin_actions ORDER BY id LIMIT 1), (SELECT id FROM users WHERE username='frank'), 'Harassment'),
  ((SELECT id FROM admin_actions ORDER BY id OFFSET 2 LIMIT 1), (SELECT id FROM users WHERE username='gina'), 'Spam');

-- Suppose one promotion
INSERT INTO promotions (admin_action_id, type, user_id) VALUES
  ((SELECT id FROM admin_actions ORDER BY id OFFSET 2 LIMIT 1), 'PromotionToModerator', (SELECT id FROM users WHERE username='frank'));

-- Admin_action_notification
INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='frank'), FALSE, now());

INSERT INTO admin_action_notifications (notification_id, admin_action_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='frank') ORDER BY id LIMIT 1),
   (SELECT id FROM admin_actions ORDER BY id OFFSET 2 LIMIT 1));

-- Moderator actions (at least 2)
INSERT INTO moderator_actions (moderator_id, date) VALUES
  ((SELECT user_id FROM moderators WHERE user_id = (SELECT id FROM users WHERE username='dave')), now() - interval '1 day'),
  ((SELECT user_id FROM moderators WHERE user_id = (SELECT id FROM users WHERE username='bob')), now() - interval '2 days');

-- Timeout & checkmark (for moderator actions)
INSERT INTO timeouts (moderator_action_id, user_id, start_time, end_time, reason) VALUES
  ((SELECT id FROM moderator_actions ORDER BY id LIMIT 1),
   (SELECT id FROM users WHERE username='judy'),
   now() - interval '2 hours',
   now() + interval '1 day',
   'Spamming');

INSERT INTO checkmarks (moderator_action_id, news_id) VALUES
  ((SELECT id FROM moderator_actions ORDER BY id OFFSET 1 LIMIT 1), 5);

-- Moderator_action_notifications
INSERT INTO notifications (user_id, seen, date) VALUES
  ((SELECT id FROM users WHERE username='judy'), FALSE, now());

INSERT INTO moderator_action_notifications (notification_id, moderator_action_id) VALUES
  ((SELECT id FROM notifications WHERE user_id = (SELECT id FROM users WHERE username='judy') ORDER BY id LIMIT 1),
   (SELECT id FROM moderator_actions ORDER BY id OFFSET 1 LIMIT 1));

-- Acknowledged reports
INSERT INTO acknowledged_reports (admin_id, report_id) VALUES
  ((SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='alice')), 2),
  ((SELECT user_id FROM admins WHERE user_id = (SELECT id FROM users WHERE username='eve')), 1);
