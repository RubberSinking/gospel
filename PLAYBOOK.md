# Lumen Verbi daily publishing playbook

Publish one data file and one original hero image each day. The page renderer is `index.php` and always opens the newest JSON file.

1. Determine the current date in `America/Vancouver`.
2. Use the official USCCB RSS feed as a date-specific source when its HTML page is blocked. First run `python3 /home/ubuntu/projects/scripts/openclaw/gospel_reading_source.py --date YYYY-MM-DD`. The helper requires an exact dated USCCB item URL, matching publication date, and one unambiguous Gospel citation. Retain its date, liturgical title, citation, item URL and feed URL in working notes. If the feed does not contain the date or verification fails, try the exact dated USCCB HTML page; never use snippets, a different date, or a generic calendar. Independently open the exact dated Vatican News Word of the Day page and confirm its visible date and Gospel citation agree. A standard `curl --fail --silent --show-error --max-time 45 URL` through exec may read either official source if web_fetch is blocked or rejects RSS; do not bypass challenges. Stop explicitly if both date-specific sources cannot be verified or their citations disagree. Use these sources for citation verification only, not copyrighted Scripture wording.
3. Retrieve the complete cited passage from a verifiable public-domain Douay-Rheims source. Create a "Douay-AI" rendering that preserves meaning, verse boundaries, sequence, names, theological claims, and Catholic terminology while modernizing archaic pronouns, verb endings, punctuation, and sentence structure. Use dignified natural English, not slang. Do not add interpretation, omit content, harmonize variants, or borrow wording from a copyrighted modern translation. Compare every finished verse against the Douay-Rheims source.
4. Locate the matching passage in St Thomas Aquinas's *Catena Aurea*. Prefer the public-domain Newman translation at CCEL or eCatholic2000. The Catena is an anthology assembled by Aquinas, so attribute each comment to the Father named in the source rather than to Aquinas.
5. Create `data/YYYY-MM-DD.json`, following the existing schema exactly. Include every verse in the day's Gospel and add `scripture_edition` and `scripture_source_url`. Label the edition exactly: "Douay-AI, modernized from the public-domain Douay-Rheims Bible." Never invent a Scripture translation.
6. For each verse, select the most illuminating patristic comment that genuinely addresses that verse or its immediate unit. Write:
   - a brief visible insight of one or two sentences;
   - a faithful modernized expansion of 70–120 words;
   - the actual author and source label.
   Modernize archaic language and explain unstated context, but do not add claims absent from the source. Preserve Catholic doctrine and the Father's line of reasoning. Do not present the modernized text as a verbatim quotation.
7. Generate a 16:9 hero image using the image-generation tool. Depict the Gospel scene as a sophisticated fusion of Byzantine mosaic, stained glass, gold leaf, and restrained futuristic luminous geometry. No text, lettering, watermark, modern objects, sentimental clip art, or celebrity likeness. Save it as `assets/YYYY-MM-DD/hero.png`.
8. Validate the JSON with `jq empty`, load the live page with `?date=YYYY-MM-DD`, and confirm every verse, insight, expandable commentary, image, edition label, and source link renders.
9. Commit only `images/gospel/` and push the Web Lab repository. Then mirror the Gospel folder to its standalone public repository with:
   `cd ~/projects/web-lab && git subtree push --prefix=images/gospel public-gospel main`
   Do not claim success unless both pushes succeed. Do not modify older daily entries.

Sources and copyright notes belong in the JSON when appropriate. Scripture and commentary must never be fabricated when a source cannot be fetched. If a source is unavailable, fail clearly rather than publishing guesses.
