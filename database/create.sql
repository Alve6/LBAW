-- Drop old schema

DROP SCHEMA IF EXISTS lbaw2511 CASCADE;
CREATE SCHEMA lbaw2511;
SET search_path TO lbaw2511;

-- Create Types

CREATE TYPE promotion_types AS ENUM ('PromotionToModerator','PromotionToAdmin');

-- Create Tables

CREATE TABLE users (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    username TEXT NOT NULL CONSTRAINT username_unique UNIQUE,
    name TEXT NOT NULL,
    email TEXT NOT NULL CONSTRAINT user_email_unique UNIQUE,
    profile_image TEXT, 
    password TEXT NOT NULL,
    description TEXT
);

CREATE TABLE admins (
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE PRIMARY KEY
);

CREATE TABLE moderators (
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE PRIMARY KEY
);

CREATE TABLE categories (
    name TEXT PRIMARY KEY
);

CREATE TABLE news (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    title TEXT NOT NULL CONSTRAINT news_title_unique UNIQUE,
    content TEXT NOT NULL,
    image TEXT,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL
);

CREATE TABLE news_category (
    news_id INTEGER REFERENCES news (id) ON UPDATE CASCADE ON DELETE CASCADE,
    category_id TEXT REFERENCES categories (name) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (news_id, category_id)
);

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    news_id INTEGER REFERENCES news (id) ON UPDATE CASCADE NOT NULL
);

CREATE TABLE votes (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    value INTEGER NOT NULL CHECK (value = -1 OR value = 1)
);

CREATE TABLE news_votes (
    vote_id INTEGER REFERENCES votes (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    news_id INTEGER REFERENCES news (id) ON UPDATE CASCADE NOT NULL,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    CONSTRAINT news_user_vote_unique UNIQUE (user_id, news_id) 
);

CREATE TABLE comment_votes (
    vote_id INTEGER REFERENCES votes (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    comment_id INTEGER REFERENCES comments (id) ON UPDATE CASCADE NOT NULL,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    CONSTRAINT comment_user_vote_unique UNIQUE (user_id, comment_id)
);

CREATE TABLE notifications (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    seen BOOLEAN NOT NULL DEFAULT FALSE,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL
);

CREATE TABLE comment_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    comment_id INTEGER REFERENCES comments (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT comm_noti_unique UNIQUE
);

CREATE TABLE vote_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    vote_id INTEGER REFERENCES votes (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT vote_noti_unique UNIQUE
);

CREATE TABLE follows (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    follower_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    followed_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    CONSTRAINT follower_followed_unique UNIQUE (follower_id, followed_id)
);

CREATE TABLE follow_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    follow_id INTEGER REFERENCES follows (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT follow_noti_unique UNIQUE
);

CREATE TABLE reports (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    content TEXT NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    target_url TEXT
);

CREATE TABLE report_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    report_id INTEGER REFERENCES reports (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT report_notification_unique UNIQUE
);

CREATE TABLE report_notifications_admins (
    report_notification_id INTEGER REFERENCES report_notifications (notification_id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    admin_id INTEGER REFERENCES admins (user_id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    PRIMARY KEY (report_notification_id, admin_id)
);

CREATE TABLE admin_actions (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    admin_id INTEGER REFERENCES admins (user_id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE admin_news_actions (
    admin_action_id INTEGER REFERENCES admin_actions (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    news_id INTEGER REFERENCES news (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    reason TEXT NOT NULL
);

CREATE TABLE admin_user_actions (
    admin_action_id INTEGER REFERENCES admin_actions (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    reason TEXT NOT NULL
);

CREATE TABLE promotions (
    admin_action_id INTEGER REFERENCES admin_actions (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    TYPE promotion_types NOT NULL,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL
);

CREATE TABLE admin_action_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    admin_action_id INTEGER REFERENCES admin_actions (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT admin_action_notification_unique UNIQUE
);

CREATE TABLE moderator_actions (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    moderator_id INTEGER REFERENCES moderators (user_id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE timeouts (
    moderator_action_id INTEGER REFERENCES moderator_actions (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE NOT NULL,
    start_time TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (start_time <= now()),
    end_time TIMESTAMP NOT NULL CHECK (end_time > start_time),
    reason TEXT NOT NULL
);

CREATE TABLE checkmarks (
    moderator_action_id INTEGER REFERENCES moderator_actions (id) ON UPDATE CASCADE ON DELETE CASCADE PRIMARY KEY,
    news_id INTEGER REFERENCES news (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL
);

CREATE TABLE moderator_action_notifications (
    notification_id INTEGER REFERENCES notifications (id) ON UPDATE CASCADE  ON DELETE CASCADE PRIMARY KEY,
    moderator_action_id INTEGER REFERENCES moderator_actions (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL CONSTRAINT mod_action_notification_unique UNIQUE
);

CREATE TABLE acknowledged_reports (
    admin_id INTEGER REFERENCES admins (user_id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    report_id INTEGER REFERENCES reports (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    PRIMARY KEY (admin_id, report_id)
);

-- Category Follows Table

CREATE TABLE category_follows (
    id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL,
    category_name TEXT REFERENCES categories (name) ON UPDATE CASCADE ON DELETE CASCADE NOT NULL, -- used cascade delete to remove follows if category is deleted
    CONSTRAINT user_category_follow_unique UNIQUE (user_id, category_name)
);


-- Create Indexes

--Performance Indexes
CREATE INDEX news_vote_ind ON news_votes USING hash (news_id);
CREATE INDEX comment_vote_ind ON comment_votes USING hash (comment_id);
CREATE INDEX notification_target ON notifications USING hash (user_id);


--FTS indexes

ALTER TABLE news ADD COLUMN tsvectors TSVECTOR;

-- Update tsvector on news insert or update
CREATE FUNCTION news_search_update() 
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A') ||
            setweight(to_tsvector('english', NEW.content), 'B') || 
            setweight( 
                COALESCE((
                    SELECT to_tsvector(
                        'english',
                        COALESCE(string_agg(content, ' '), '')
                    )
                    FROM comments
                    WHERE news_id = NEW.id
                ), to_tsvector('english', '')),'C'
            )
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN    
        -- Image updates do not require a recalculation
        IF (NEW.title <> OLD.title OR NEW.content <> OLD.content) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
                setweight(to_tsvector('english', NEW.content), 'B') || 
                setweight( 
                    COALESCE((
                        SELECT to_tsvector(
                            'english',
                            COALESCE(string_agg(content, ' '), '')
                        )
                        FROM comments
                        WHERE news_id = NEW.id
                    ), to_tsvector('english', '')),'C'
                )
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Trigger on insert or update of a news
CREATE TRIGGER news_search_update
BEFORE INSERT OR UPDATE ON news
FOR EACH ROW
EXECUTE PROCEDURE news_search_update();

-- Update tsvector on comments insert or update (comments can only get content updated)
CREATE FUNCTION news_com_search_update() 
RETURNS TRIGGER AS $$
BEGIN
    UPDATE news
    SET tsvectors = (
            setweight(to_tsvector('english', title), 'A') ||
            setweight(to_tsvector('english', content), 'B') || 
            setweight((
                SELECT to_tsvector('english', string_agg(content, ' '))
                FROM comments
                WHERE news_id = NEW.news_id
            ), 'C')
        )
    WHERE id = NEW.news_id;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Trigger on all comment operations, after for the comment to count
CREATE TRIGGER news_com_search_update
AFTER INSERT OR UPDATE OR DELETE ON comments
FOR EACH ROW
EXECUTE PROCEDURE news_com_search_update();

CREATE INDEX news_search_idx ON news USING GIST (tsvectors);

--Triggers

--TRIGGER01
--A user cannot remove their published content if it has received any votes or comments

CREATE OR REPLACE FUNCTION prevent_deletion_if_voted_or_commented()
RETURNS TRIGGER AS $$
DECLARE
    vote_count INTEGER;
    comment_count INTEGER;
    reasons TEXT[] := ARRAY[]::TEXT[];
    error_message TEXT;
BEGIN
    IF TG_TABLE_NAME = 'news' THEN
        SELECT COUNT(*) INTO vote_count FROM news_votes WHERE news_id = OLD.id;
        SELECT COUNT(*) INTO comment_count FROM comments WHERE news_id = OLD.id;

        IF vote_count > 0 THEN
            reasons := array_append(reasons, format('%s votes', vote_count));
        END IF;
        
        IF comment_count > 0 THEN
            reasons := array_append(reasons, format('%s comments', comment_count));
        END IF;
        
        IF array_length(reasons, 1) > 0 THEN
            error_message := 'Cannot delete news article: it has received ' || array_to_string(reasons, ' and ') || '.';
            RAISE EXCEPTION '%', error_message;
        END IF;

    ELSIF TG_TABLE_NAME = 'comments' THEN
        SELECT COUNT(*) INTO vote_count FROM comment_votes WHERE comment_id = OLD.id;

        IF vote_count > 0 THEN
            RAISE EXCEPTION 'Cannot delete comment: it has received % votes.', vote_count;
        END IF;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_prevent_news_delete
BEFORE DELETE ON news
FOR EACH ROW
EXECUTE FUNCTION prevent_deletion_if_voted_or_commented();

CREATE TRIGGER trg_prevent_comment_delete
BEFORE DELETE ON comments
FOR EACH ROW
EXECUTE FUNCTION prevent_deletion_if_voted_or_commented();

--TRIGGER02
--A moderator can't mark their own news article as trustworthy (with the checkmark)

CREATE OR REPLACE FUNCTION prevent_self_checkmark()
RETURNS TRIGGER AS $$
DECLARE
    news_author INTEGER;
    acting_moderator INTEGER;
BEGIN
    SELECT user_id INTO news_author FROM news WHERE id = NEW.news_id;
    SELECT moderator_id INTO acting_moderator FROM moderator_actions WHERE id = NEW.moderator_action_id;

    IF news_author = acting_moderator THEN
        RAISE EXCEPTION 'Moderator cannot checkmark their own news.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_prevent_self_checkmark
BEFORE INSERT ON checkmarks
FOR EACH ROW
EXECUTE FUNCTION prevent_self_checkmark();

--TRIGGER03
--The publishing date of posting of a comment to a news article must be greater than the date of posting of the respective news article

CREATE OR REPLACE FUNCTION validate_comment_date()
RETURNS TRIGGER AS $$
DECLARE
    news_date TIMESTAMP WITH TIME ZONE;
BEGIN
    SELECT date INTO news_date FROM news WHERE id = NEW.news_id;

    IF NEW.date <= news_date THEN
        RAISE EXCEPTION 'Comment date (%s) must be after the news date (%s).',
        NEW.date, news_date;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_validate_comment_date
BEFORE INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION validate_comment_date();

--TRIGGER04
--Upon account deletion, public user data, like comments, news articles and likes are not erased, but anonymised

CREATE OR REPLACE FUNCTION anonymize_user_data()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.username LIKE 'deleted_user_%' THEN
        RAISE EXCEPTION 'Cannot delete an already anonymized account.';
    END IF;

    UPDATE users
    SET 
        username = CONCAT('deleted_user_', OLD.id),
        name = 'Deleted User',
        email = CONCAT('deleted_', OLD.id, '@system.local'),
        password = 'deleted_account',
        description = NULL
    WHERE id = OLD.id;

    RAISE NOTICE 'User % has been anonymized instead of deleted.', OLD.id;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_anonymize_user_data
BEFORE DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION anonymize_user_data();
