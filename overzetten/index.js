const mysql = require('promise-mysql')

const conn = mysql.createPool({
  host: 'localhost',
  user: 'test',
  password: 'test',
  database: 'forum',
  connectionLimit: 10,
})

const dhc = mysql.createPool({
  host: 'localhost',
  user: 'test',
  password: 'test',
  database: 'dhc',
  connectionLimit: 10,
})

const topicIds = {}
const newsCats = {}

const replaceIcons = (str) => {
  let newStr = str;
  if((newStr.indexOf(".gif") - newStr.indexOf("/img/icons/icon_")) < 36) {
    newStr = newStr.replace("/img/icons/icon_", "/images/emoji/")
    newStr = newStr.replace(".gif", ".png")
  }
  if((newStr.indexOf(".gif") - newStr.indexOf("/img/icons/")) < 36) {
    newStr = newStr.replace("/img/icons/", "/images/emoji/")
    newStr = newStr.replace(".gif", ".png")
  }
  return newStr;
}

dhc
  .query('SELECT *, profiles.id FROM profiles JOIN users ON profiles.user_id = users.id')
  .then(res => {
    const queries = []
    res.forEach((x, i) => {
      let role_id = 2
      if (x.group_id == 500 || x.group_id == 502 || x.group_id == 400) {
        role_id = 5
      } else if (x.group_id == 300) {
        role_id = 4
      }
      queries.push(
        (x.uuid ? conn.query("INSERT INTO image(path) VALUES(?)", [`/profile/${x.id}-${x.uuid}.${x.extension}`]).catch(e => console.log(e)) : new Promise(res => res())).then(res =>
        conn
          .query(
            'INSERT INTO user(id, email, first_name, last_name, username, created_at, last_changed, role_id, city, news, birthdate, signature, profile_img) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (SELECT id FROM image WHERE path = ?))',
            [
              x.id,
              x.email,
              x.username
                .split(' ')
                .slice(0, 1)
                .join('')
                .trim(),
              x.username
                .split(' ')
                .slice(1)
                .join(' ')
                .trim(),
              x.username,
              x.created,
              x.modified,
              role_id,
              x.location,
              x.newsletter,
              x.birthdate,
              x.signature_cache,
              x.uuid ? `/profile/${x.id}-${x.uuid}.${x.extension}` : `/default.jpg`
            ]
          )
          .catch(e => console.log(e))
      ).catch(e => console.log(e)))
    })
    return Promise.all(queries)
  })
  .then(res => conn.query(
      "INSERT INTO user (first_name, last_name, email, username, password, role_id, created_at) VALUES ('john', 'doe', 'john_doe@example.com', 'test', '$2y$10$9UNJC27kiVGmXrn5WUeyPeSktXXF1uTRE2mX8bgOISy2GTLC57pBm', 5, NOW()), ('jane', 'doe', 'jane_doe@example.com', 'bla', '$2y$10$9UNJC27kiVGmXrn5WUeyPeSktXXF1uTRE2mX8bgOISy2GTLC57pBm', 5, NOW());",
    ).catch(e => console.log(e))
  )
  .then(res => dhc.query("SELECT * FROM forums"))
  .then(res => {
    const queries = []
    res.forEach((x, i) => {
      if (x.is_category == 1) {
        queries.push(conn.query(
          'INSERT INTO category(id, name, created_at) VALUES(?, ?, NOW())',
          [x.id, x.title]
        ).then(res => conn.query("INSERT INTO category_permission(role_id, category_id) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)", [1, x.id, 2, x.id, 3, x.id, 4, x.id, 5, x.id])).catch(e => console.log(e)))
      } else {
        queries.push(conn.query(
          'INSERT INTO sub_category(id, name, created_at, category_id) VALUES(?, ?, NOW(), ?)',
          [x.id, x.title, x.parent_id]
        ).then(res => conn.query("INSERT INTO sub_category_permission(role_id, sub_category_id) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)", [1, x.id, 2, x.id, 3, x.id, 4, x.id, 5, x.id])).catch(e => console.log(e)))
      }
    })
    return Promise.all(queries)
  })
  .then(res => dhc.query("SELECT * FROM forum_topics"))
  .then(res => {
    const queries = []
    res.forEach((x, i) => {
      let state = 1
      if (x.is_sticky) {
        state = 3
      }
      if (x.is_locked) {
        state = 2
      }
      let forum_topic_id = null;
      queries.push(
        dhc.query("SELECT * FROM forum_posts WHERE profile_id = ? AND forum_id = ? AND created < (? + INTERVAL 1 MINUTE) AND created > (? - INTERVAL 1 MINUTE) ORDER BY created DESC LIMIT 1", [x.profile_id, x.forum_id, x.created, x.created]).then(res => {
          if(res.length > 0) {
            forum_topic_id = res[0].forum_topic_id
            return conn.query(
              'INSERT INTO topic(user_id, title, sub_category_id, created_at, last_changed, state_id, content) VALUES (?, ?, ?, ?, ?, ?, ?)',
              [x.profile_id, x.title, x.forum_id, x.created, x.modified, state, replaceIcons(res[0].content_cache)]
            ).catch(e => console.log(e))
          } else {
            return Promise.reject("no content found")
          }
        }).then(res => conn.query("SELECT id FROM topic WHERE user_id = ? AND created_at = ?", [x.profile_id, x.created])).catch(e => console.log(e))
         .then(res => {
            let id = false;
            if(res && res.length > 0) {
              id = {dhc: forum_topic_id, conn: res[0].id}
            }
            return new Promise((res, rej) => {
              if(id) {
                res(id)
              } else {
                rej()
              }
            })
         }).catch(e => console.log(e))
      )
    })
    return Promise.all(queries)
  })
  .then(res => {
    res.forEach((x, i) => {
      if(x) {
        topicIds[x.dhc] = x.conn;
      }
    })
    return dhc.query("SELECT * FROM forum_posts")
  })
  .then(res => {
    const queries = []
    res.forEach((x, i) => {
      queries.push(
        conn.query("SELECT * FROM topic WHERE created_at < (? + INTERVAL 5 SECOND) AND created_at > (? - INTERVAL 5 SECOND) AND user_id = ? AND sub_category_id = ?", [x.created, x.created, x.profile_id, x.forum_id])
        .then(res => res.length === 0 ? conn.query("INSERT INTO reply(user_id, content, topic_id, created_at, last_changed) VALUES(?, ?, ?, ?, ?)",
                                        [x.profile_id, replaceIcons(x.content_cache), topicIds[x.forum_topic_id], x.created, x.modified]).catch(e => console.log(e))
                                      : conn.query("INSERT INTO topic_permission(role_id, topic_id) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)", [1, topicIds[x.forum_topic_id], 2, topicIds[x.forum_topic_id], 3, topicIds[x.forum_topic_id], 4, topicIds[x.forum_topic_id], 5, topicIds[x.forum_topic_id]]).catch(e => console.log(e))
      ))
    })
    return Promise.all(queries);
  })
  .then(res => dhc.query("SELECT * FROM newscategories"))
  .then(res => Promise.all(res.map(x => conn.query(
    "INSERT INTO sub_category(category_id, name, created_at) VALUES(?, ?, NOW())",
    [1, x.name]
  ).catch(e => console.log(e)).then(res => conn.query("SELECT id FROM sub_category WHERE name = ?", [x.name])).then(res => Promise.resolve({conn: res[0].id, dhc: x.id})).catch(e => console.log(e)))))
  .then(res => {
    res.forEach((x, i) => {
      newsCats[x.dhc] = x.conn;
    })
    console.log(newsCats)
    return dhc.query("SELECT * FROM news");
  })
  .then(res => {
    const queries = []
    res.forEach(x => queries.push(conn.query("INSERT INTO news(id, sub_category_id, title, content, created_at, last_changed) VALUES(?, ?, ?, ?, ?, ?)",
                                             [x.id, newsCats[x.newscategory_id], x.title, x.body, x.created, x.modified]).then(res =>
                                              conn.query("INSERT INTO news_permission(role_id, news_id) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)",
                                              [1, x.id, 2, x.id, 3, x.id, 4, x.id, 5, x.id])).catch(e => console.log(e))))
    return Promise.all(queries)
  })
  .then(res => dhc.query("SELECT *, comments_news.created, comments_news.modified, comments_news.message, profiles.id as profile_id FROM comments_news JOIN profiles ON profiles.user_id = comments_news.user_id"))
  .then(res => {
    const queries = []
    res.forEach(x => {
      queries.push(conn.query("INSERT INTO news_reply(user_id, content, news_id, created_at, last_changed) VALUES(?, ?, ?, ?, ?)", [x.profile_id, x.message, x.foreign_id, x.created, x.modified]).catch(e => console.log(e)))
    })
    return Promise.all(queries)
  })


  .then(res => dhc.query("SELECT *, gallery_pictures.id FROM gallery_pictures JOIN galleries ON galleries.id = gallery_pictures.gallery_id"))
  .then(res => {
    const queries = []
    res.forEach(x => {
      queries.push(conn.query("INSERT INTO album(id, title, created_at, user_id) VALUES(?, ?, ?, ?)", [x.gallery_id, x.title, x.created, x.profile_id]).catch(e =>
        conn.query("INSERT INTO image(path, album_id) VALUES(?, ?)", [`/album/${x.id}-${x.uuid}.${x.extension}`, x.gallery_id]).then(res => {}).catch(e => console.log(e))
      ).then(res => conn.query("INSERT INTO image(path, album_id) VALUES(?, ?)", [`/album/${x.id}-${x.uuid}.${x.extension}`, x.gallery_id]).catch(e => console.log(e)))
    )
    })
    return Promise.all(queries);
  })
  .then(res => dhc.query("SELECT *, comments_gallery_pictures.created, comments_gallery_pictures.modified, profiles.id as profile_id FROM comments_gallery_pictures JOIN gallery_pictures ON foreign_id = gallery_pictures.id JOIN profiles ON profiles.user_id = comments_gallery_pictures.user_id"))
  .then(res => {
    const queries = [];
    res.forEach(x => {
      queries.push(conn.query("INSERT INTO album_reply(user_id, album_id, content, created_at, last_changed) VALUES (?, ?, ?, ?, ?)", [x.profile_id, x.gallery_id, x.message, x.created, x.modified]).catch(e => console.log(e)))
    });
    return Promise.all(queries)
  })
  .then(res => console.log("finished"))
  .catch(e => console.log(e))