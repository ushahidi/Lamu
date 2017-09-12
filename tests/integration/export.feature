@post @oauth2Skip
Feature: Testing the Export API
	@resetFixture @csvexport
	Scenario: Search All Posts and export the results
		Given that I want to get all "Posts"
		When I request "/posts/export"
		And that the response "Content-Type" header is "text/csv"
		Then the csv response body should have heading:
			"""
			author_email,author_realname,color,completed_stages.0,completed_stages.1,contact_id,content,created,form_id,form_name,id,locale,message_id,parent_id,post_date,published_to.0,sets.0,slug,source,status,tags.0,tags.1,title,type,updated,user_id,"Test varchar.0","Test varchar.1",Categories.0,Categories.1,"Last Location (point).lat","Last Location (point).lon","Last Location.0","Geometry test.0",Status.0,"Second Point.lat","Second Point.lon",Links.0,Links.1,"Person Status.0","Test Field Level Locking 3.0","Test Field Level Locking 4.0","Test Field Level Locking 5.0","Test Field Level Locking 6.0","Test Field Level Locking 7.0"
			"""