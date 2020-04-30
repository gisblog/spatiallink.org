<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../languages/lang_pl.php

//translator(s): Dariusz Kowalski <d.kowalski@dreamnet.waw.pl>
$setCharset = "ISO-8859-2";

$byteUnits = array('Bytes', 'KB', 'MB', 'GB');

$dayNameArray = array(1 =>"Monday", 2 =>"Tuesday", 3 =>"Wednesday", 4 =>"Thursday", 5 =>"Friday", 6 =>"Saturday", 7 =>"Sunday");

$monthNameArray = array(1=> "Stycze�", "Luty", "Marzec", "Kwiecie�", "Maj", "Czerwiec", "Lipiec", "Sierpie�", "Wrzesie�", "Pa�dziernik", "Listopad", "Grudzie�"); 

$status = array(0 => "Oddany Klientowi", 1 => "Sko�czony", 2 => "Nie rozpocz�ty", 3 => "Rozpocz�ty", 4 => "Zawieszony");

$profil = array(0 => "Administrator", 1 => "Menager Projektu", 2 => "U�ytkownik", 3 => "Klient", 4 => "Disabled", 5 => "Project Manager Administrator");

$priority = array(0 => "Brak", 1 => "Bardzo niski", 2 => "Niski", 3 => "�redni", 4 => "Wysoki", 5 => "Bardzo wysoki");

$statusTopic = array(0 => "Zamkni�te", 1 => "Otwarte");
$statusTopicBis = array(0 => "Tak", 1 => "Nie");

$statusPublish = array(0 => "Tak", 1 => "Nie");

$statusFile = array(0 => "Zatwierdzony", 1 => "Zatwierdzony ze zmianami", 2 => "Potrzebuje zatwierdzenia", 3 => "Nie potrzebuje zatwierdzenia", 4 => "Nie zatwierdzony");

$phaseStatus = array(0 => "Not started", 1 => "Open", 2 => "Complete", 3 => "Suspended");

$requestStatus = array(0 => "New", 1 => "Open", 2 => "Complete");

$strings["please_login"] = "Zaloguj si�";
$strings["requirements"] = "Wymagania";
$strings["login"] = "Logowanie";
$strings["no_items"] = "Brak element�w do wy�wietlenia";
$strings["logout"] = "Wyloguj";
$strings["preferences"] = "Ustawienia";
$strings["my_tasks"] = "Moje Zadania";
$strings["edit_task"] = "Edytuj Zadanie";
$strings["copy_task"] = "Skopiuj Zadanie";
$strings["add_task"] = "Dodaj Zadanie";
$strings["delete_tasks"] = "Skasuj Zadanie";
$strings["assignment_history"] = "Historia przypisa�";
$strings["assigned_on"] = "Dnia";
$strings["assigned_by"] = "Przypisany przez";
$strings["to"] = "Do";
$strings["comment"] = "Komentarz";
$strings["task_assigned"] = "Zadanie przypisane do";
$strings["task_unassigned"] = "Zadanie nie jest przypisane do nikogo (Nie przypisane)";
$strings["edit_multiple_tasks"] = "Zmie� wielokrotne zadania";
$strings["tasks_selected"] = "zadania wybrane. Wybierz nowe warto�ci dla tego zadania, Lub wybierz [Brak zmian], aby pozosta� przy dodychczasowych";
$strings["assignment_comment"] = "Komentarz do przypisania";
$strings["no_change"] = "[Brak zmian]";
$strings["my_discussions"] = "Moje Dyskusje";
$strings["discussions"] = "Dyskusje";
$strings["delete_discussions"] = "Skasuj Dyskusje";
$strings["delete_discussions_note"] = "Uwaga: Dyskusja nie mo�e zosta� ponownie otworzona, le�eli zosta�a ju� skasowana.";
$strings["topic"] = "Temat";
$strings["posts"] = "Wiadomo�ci";
$strings["latest_post"] = "Najnowsza wiadomo��";
$strings["my_reports"] = "Moje Raporty";
$strings["reports"] = "Raport";
$strings["create_report"] = "Stw�rz Raport";
$strings["report_intro"] = "Wybierz parametry swojego raportu i zachowaj je po jego uruchomieniu";
$strings["admin_intro"] = "Konfiguracja projektu.";
$strings["copy_of"] = "Kopia ";
$strings["add"] = "Dodaj";
$strings["delete"] = "Skasuj";
$strings["remove"] = "Usu�";
$strings["copy"] = "Skopiuj";
$strings["view"] = "Poka�";
$strings["edit"] = "Zmie�";
$strings["update"] = "Zmie�";
$strings["details"] = "Szczeg�y";
$strings["none"] = "Brak";
$strings["close"] = "Zamknij";
$strings["new"] = "Nowy";
$strings["select_all"] = "Wybierz wszystkie";
$strings["unassigned"] = "Nie przypisany";
$strings["administrator"] = "Administrator";
$strings["my_projects"] = "Moje Projekty";
$strings["project"] = "Projekt";
$strings["active"] = "Aktywny";
$strings["inactive"] = "Nieaktywny";
$strings["project_id"] = "ID Projektu";
$strings["edit_project"] = "Edytuj Projekt";
$strings["copy_project"] = "Kopiuj Projekt";
$strings["add_project"] = "Dodaj Projekt";
$strings["clients"] = "Klienci";
$strings["organization"] = "Klient";
$strings["client_projects"] = "Projekty Klienta";
$strings["client_users"] = "U�ytkownicy Klienta";
$strings["edit_organization"] = "Zmie� dane klienta";
$strings["add_organization"] = "Dodaj dane klienta";
$strings["organizations"] = "Klienci";
$strings["info"] = "Info";
$strings["status"] = "Status";
$strings["owner"] = "W�a�ciciel";
$strings["home"] = "Strona domowa";
$strings["projects"] = "Projekty";
$strings["files"] = "Pliki";
$strings["search"] = "Szukaj";
$strings["admin"] = "Admin";
$strings["user"] = "U�ytkownik";
$strings["project_manager"] = "Menager Projektu";
$strings["due"] = "Do";
$strings["task"] = "Zadanie";
$strings["tasks"] = "Zadania";
$strings["team"] = "Zesp�";
$strings["add_team"] = "Dodaj cz�onka zespo�u";
$strings["team_members"] = "Cz�onkowie zespo�u";
$strings["full_name"] = "Imi� i Nazwisko";
$strings["title"] = "Stanowisko";
$strings["user_name"] = "Nazwa u�ytkownika";
$strings["work_phone"] = "Tel. do pracy";
$strings["priority"] = "Priorytet";
$strings["name"] = "Nazwa";
$strings["id"] = "ID";
$strings["description"] = "Opis";
$strings["phone"] = "Tel.";
$strings["url"] = "URL";
$strings["address"] = "Adres";
$strings["comments"] = "Komentarze";
$strings["created"] = "Utworzony";
$strings["assigned"] = "Przypisany";
$strings["modified"] = "Zmodyfikowany";
$strings["assigned_to"] = "Przypisany do";
$strings["due_date"] = "Do dnia";
$strings["estimated_time"] = "Przybli�ony czas";
$strings["actual_time"] = "Aktualny czas";
$strings["delete_following"] = "Skasowa� to co jest poni�ej?";
$strings["cancel"] = "Anuluj";
$strings["and"] = "oraz";
$strings["administration"] = "Administracja";
$strings["user_management"] = "Zarz�dzanie u�ytkownikami";
$strings["system_information"] = "Informacje o systemie";
$strings["product_information"] = "Informacje o produkcie";
$strings["system_properties"] = "Ustawienia systemu";
$strings["create"] = "Stw�rz";
$strings["report_save"] = "Zachowaj to zapytanie na twojej stronie domowej, b�dziesz m�g� je wykorzysta� jeszcze raz.";
$strings["report_name"] = "Nazwa raportu";
$strings["save"] = "Zachowaj";
$strings["matches"] = "Znalezionych";
$strings["match"] = "Pasuj�cy";
$strings["report_results"] = "Wyniki Raportu";
$strings["success"] = "Wynik";
$strings["addition_succeeded"] = "Dodano";
$strings["deletion_succeeded"] = "Skasowano";
$strings["report_created"] = "Raport stworzony";
$strings["deleted_reports"] = "Raporty usuni�te";
$strings["modification_succeeded"] = "Zmodyfikowane";
$strings["errors"] = "B��d!";
$strings["blank_user"] = "U�ytkownik nie zosta� znaleziony";
$strings["blank_organization"] = "Klient nie mo�e by� zlokalizowany.";
$strings["blank_project"] = "Projekt nie mo�e by� zlokalizowany.";
$strings["user_profile"] = "Profil u�ytkownika";
$strings["change_password"] = "Zmie� has�o";
$strings["change_password_user"] = "Zmie� has�o u�ytkownika.";
$strings["old_password_error"] = "Stare has�o jest niepoprawne. Prosze poda� je jeszcze raz.";
$strings["new_password_error"] = "Dwa podane przez Ciebie has�a nie zgadzaj� si�. Prosz� o ich powt�rbne wpisane.";
$strings["notifications"] = "Zawiadomienie";
$strings["change_password_intro"] = "Wpisz swoje stare has�o, potem wpisz dwa razy nowe has�o.";
$strings["old_password"] = "Stare has�o";
$strings["password"] = "Has�o";
$strings["new_password"] = "Nowe has�o";
$strings["confirm_password"] = "Potwierd� has�o";
$strings["email"] = "E-Mail";
$strings["home_phone"] = "Tel. do domu";
$strings["mobile_phone"] = "Tel. kom.";
$strings["fax"] = "Fax";
$strings["permissions"] = "Prawa";
$strings["administrator_permissions"] = "Prawa Administratora";
$strings["project_manager_permissions"] = "Prawa Menagera Projekt�w";
$strings["user_permissions"] = "Prawa U�ytkownika";
$strings["account_created"] = "Konto za�o�one";
$strings["edit_user"] = "Zmie� dane u�ytkownika";
$strings["edit_user_details"] = "Edytuj dane konta u�ytkownika.";
$strings["change_user_password"] = "Zmie� has�o u�ytkownika.";
$strings["select_permissions"] = "Wybierz prawa dla tego u�ytkownika";
$strings["add_user"] = "Dodaj u�ytkownika";
$strings["enter_user_details"] = "Podaj szczeg�y dla nowo tworzonego konta u�ytkownika.";
$strings["enter_password"] = "Podaj has�o u�ytkownik�w.";
$strings["success_logout"] = "Wylogowa�e� si�. Mo�esz si� zalogowa� ponownie, wystarczy, �e wpiszesz sw�j login i has�o.";
$strings["invalid_login"] = "Login lub/i has�o kt�re poda�e� s� nieprawid�owe. Spr�buj jeszcze raz.";
$strings["profile"] = "Profil";
$strings["user_details"] = "Szczeg�y konta u�ytkownika.";
$strings["edit_user_account"] = "Edytuj dane twojego konta.";
$strings["no_permissions"] = "Nie masz wystarczaj�cych praw do wykonania tej akcji.";
$strings["discussion"] = "Dyskusja";
$strings["retired"] = "Osamotniony";
$strings["last_post"] = "Ostatnia wiadomo��";
$strings["post_reply"] = "Odpowiedz";
$strings["posted_by"] = "Wys�ane przez";
$strings["when"] = "Kiedy";
$strings["post_to_discussion"] = "Wy�lij do dyskusji";
$strings["message"] = "Wiadomo��";
$strings["delete_reports"] = "Usu� raparty";
$strings["delete_projects"] = "Usu� projekty";
$strings["delete_organizations"] = "Usu� klienta";
$strings["delete_organizations_note"] = "UWAGA: Skasujesz wszytskich u�ytkownik�w dla tego klienta i spowod�jesz od��czenie wszystkich projekt�w od tego klienta.";
$strings["delete_messages"] = "Usu� wiadomo�ci";
$strings["attention"] = "Uwaga";
$strings["delete_teamownermix"] = "Usuni�cie zako�czone powodzeniem, ale w�a�ciciel projektu nie mo�e by� usuni�ty z grupy projektowej.";
$strings["delete_teamowner"] = "Nie mo�esz usun�� w�a�ciciela projektu z grupy projektowej.";
$strings["enter_keywords"] = "Podaj szukane s�owa";
$strings["search_options"] = "Szukane s�owa i opcje wyszukiwarki";
$strings["search_note"] = "Musisz poda� jakie� s�owo w formularzu wyszukiwarki";
$strings["search_results"] = "Wyniki wyszukiwania";
$strings["users"] = "U�ytkownicy";
$strings["search_for"] = "Szukaj";
$strings["results_for_keywords"] = "Wyniki wyszukiwania dla s��w:";
$strings["add_discussion"] = "Dodaj dyskusj�";
$strings["delete_users"] = "Skasuj konta u�ytkownika";
$strings["reassignment_user"] = "Zmiana przypisa� do projekt�w i zada�";
$strings["there"] = "Tam jest";
$strings["owned_by"] = "w�a�cicielem s� osoby wypisane poni�ej.";
$strings["reassign_to"] = "Zamin skasujesz u�ytkownik�w, przypisz to do";
$strings["no_files"] = "Nie ma do��czonych �adnych plik�w";
$strings["published"] = "Opublikowany";
$strings["project_site"] = "Strona projektu";
$strings["approval_tracking"] = "Zatwierdzenie";
$strings["size"] = "Wielko��";
$strings["add_project_site"] = "Dodaj do strony projektu";
$strings["remove_project_site"] = "Usu� ze strony projektu";
$strings["more_search"] = "Wi�cej opcji wyszukiwarki";
$strings["results_with"] = "Szukaj wyniki z";
$strings["search_topics"] = "Szukaj tematu";
$strings["search_properties"] = "Szukaj w�a�ciwo�ci";
$strings["date_restrictions"] = "Po dacie";
$strings["case_sensitive"] = "Wra�liwo�� na wielko�� liter";
$strings["yes"] = "Tak";
$strings["no"] = "Nie";
$strings["sort_by"] = "Sortuj po";
$strings["type"] = "Rodzaj";
$strings["date"] = "Data";
$strings["all_words"] = "wszystkie s�owa";
$strings["any_words"] = "kt�rekolwiek ze s�ow";
$strings["exact_match"] = "dok�adny wynik";
$strings["all_dates"] = "Oboj�tne";
$strings["between_dates"] = "Pomi�dzy dniami";
$strings["all_content"] = "Ca�a zawarto��";
$strings["all_properties"] = "Wszytskie w�a�ciwo�ci";
$strings["no_results_search"] = "Wyszukiwarka nic nie znalaz�a.";
$strings["no_results_report"] = "Raport nie zwr�ci� �adnych wynik�w.";
$strings["schema_date"] = "RRRR/MM/DD";
$strings["hours"] = "godzin";
$strings["choice"] = "Wybierz";
$strings["missing_file"] = "Brak pliku!";
$strings["project_site_deleted"] = "Strona projektu zosta�a skasowana.";
$strings["add_user_project_site"] = "Prawa dla u�ytkownika zosta�y nadane do dost�pu do strony projektu.";
$strings["remove_user_project_site"] = "Prawa dla u�ytkownika zosta�y usuni�te.";
$strings["add_project_site_success"] = "Dodawanie do strony projektu zako�czone sukcesem.";
$strings["remove_project_site_success"] = "Usuwanie ze strony projektu zako�czone sukcesem.";
$strings["add_file_success"] = "Plik zosta� dodany.";
$strings["delete_file_success"] = "Plik zosta� skasowany.";
$strings["update_comment_file"] = "Komentarz do pliku zosta� zmieniony.";
$strings["session_false"] = "B��d sesji";
$strings["logs"] = "Logi";
$strings["logout_time"] = "Czas automatycznego wylogowania";
$strings["noti_foot1"] = "To powiadomienie zosta�o wygenerowane przez PhpCollab.";
$strings["noti_foot2"] = "Aby wej�� na stron� PhpCollab, wpisz:";
$strings["noti_taskassignment1"] = "Nowe zadanie:";
$strings["noti_taskassignment2"] = "Zadanie zosta�o przypisane tobie:";
$strings["noti_moreinfo"] = "Aby zobaczy� wi�cej informacji wejd� na:";
$strings["noti_prioritytaskchange1"] = "Zmiana priorytetu:";
$strings["noti_prioritytaskchange2"] = "Priorytet poni�szego zadania zosta� zmieniony na:";
$strings["noti_statustaskchange1"] = "Zmiana statusu:";
$strings["noti_statustaskchange2"] = "Status poni�szego zadania zosta� zmieniony:";
$strings["login_username"] = "Musisz poda� nazw� u�ytkownika";
$strings["login_password"] = "Prosz� poda� has�o";
$strings["login_clientuser"] = "To jest konto klienta. Nie masz dost�pu z takim kontem do PhpCollab.";
$strings["user_already_exists"] = "Jest ju� u�ytkownik o takiej nazwie. Prosz� wybra� inn� nazw u�ytkownika";
$strings["noti_duedatetaskchange1"] = "Zmiana terminu zako�czenia zadania:";
$strings["noti_duedatetaskchange2"] = "Termin zako�czenia poni�szego zadania zosta� zmieniony:";
$strings["company"] = "Firma";
$strings["show_all"] = "Poka� wszystko";
$strings["information"] = "Informacje";
$strings["delete_message"] = "Skasuj t� wiadomo��";
$strings["project_team"] = "Zesp�";
$strings["document_list"] = "Lista dokument�w";
$strings["bulletin_board"] = "Biuletyn";
$strings["bulletin_board_topic"] = "Temat biuletynu";
$strings["create_topic"] = "Stw�rz nowy temat";
$strings["topic_form"] = "Formularz Tematu";
$strings["enter_message"] = "Podaj swoj� wiadomo��";
$strings["upload_file"] = "Zamie�� plik";
$strings["upload_form"] = "Fromularz zamieszczania plik�w";
$strings["upload"] = "Zamie�� na serwerze";
$strings["document"] = "Dokument";
$strings["approval_comments"] = "Zatwierd� komentarze";
$strings["client_tasks"] = "Zadania klienta";
$strings["team_tasks"] = "Zadania zespo�u";
$strings["team_member_details"] = "Szczeg�y dotycz�ce cz�onka zespo�u projektowego";
$strings["client_task_details"] = "Szczeg�y dotycz�ce zadania";
$strings["team_task_details"] = "Szczeg�y dotycz�ce zespo�u projektowego";
$strings["language"] = "J�zyk";
$strings["welcome"] = "Witamy";
$strings["your_projectsite"] = "do twojej strony projektu";
$strings["contact_projectsite"] = "Je�eli masz jakie� pytania dotycz�ce informacji kt�re tutaj znalaz�e�, skontaktuj si� z w�a�cicielem projektu.";
$strings["company_details"] = "Informacje o firmie";
$strings["database"] = "Backup i odtwarzenie bazy danych";
$strings["company_info"] = "Zmie� dane twojej firmy";
$strings["create_projectsite"] = "Stw�rz stron� projektu";
$strings["projectsite_url"] = "URL strony projektu";
$strings["design_template"] = "Wzorzec";
$strings["preview_design_template"] = "Zobacz Wzorzec";
$strings["delete_projectsite"] = "Skasuj stron� projektu";
$strings["add_file"] = "Dodaj plik";
$strings["linked_content"] = "Zawarto�� projektu";
$strings["edit_file"] = "Zmie� dane pliku";
$strings["permitted_client"] = "U�ytkonicy klienta maj�cy prawa";
$strings["grant_client"] = "Nadaj prawa aby mo�na by�o ogl�da� stron� projektu.";
$strings["add_client_user"] = "Dodaj u�ytkownika klienta";
$strings["edit_client_user"] = "Zmie� dane u�ytkownika klienta";
$strings["client_user"] = "U�ytkownicy klienta";
$strings["client_change_status"] = "Je�eli sko�czy�e� to zadanie, zmie� jego status (znajduj�cy si� poni�ej)";
$strings["project_status"] = "Status Projektu";
$strings["view_projectsite"] = "Widok strony projektu";
$strings["enter_login"] = "Podaj sw�j login, aby uzyska� nowe has�o";
$strings["send"] = "Wy�lij";
$strings["no_login"] = "Nie ma takiej nazwy u�ytkownika w bazie";
$strings["email_pwd"] = "Has�o wys�ane";
$strings["no_email"] = "U�ytkownik bez adresu email";
$strings["forgot_pwd"] = "Zapomnia�e� has�a?";
$strings["project_owner"] = "Mo�esz wykonywa� zmiany tylko na swoich w�asnych projektach.";
$strings["connected"] = "Po��czony";
$strings["session"] = "Sesja";
$strings["last_visit"] = "Ostatnia wizyta";
$strings["compteur"] = "Ile razy	";
$strings["ip"] = "Ip";
$strings["task_owner"] = "Nie jeste� cz�onkiem zespo�u projektowego";
$strings["export"] = "Export";
$strings["browse_cvs"] = "Przegl�daj CVS";
$strings["repository"] = "Repozytorium CVS";
$strings["reassignment_clientuser"] = "Ponowne przypisanie do zadania";
$strings["organization_already_exists"] = "Ta nazwa znajduje si� ju� w systemie. Prosz� wybra� inn�.";
$strings["blank_organization_field"] = "Musisz poda� nazw� klienta.";
$strings["blank_fields"] = "pole obowi�zkowe";
$strings["projectsite_login_fails"] = "Nie mo�emy zatwierdzi� takiej kominacji nazwy u�ytkownika i has�a.";
$strings["start_date"] = "Rozpocz�to";
$strings["completion"] = "Post�p";
$strings["update_available"] = "Jest ju� dost�pne Uaktualnienie!";
$strings["version_current"] = "Obecnie u�ywasz wersji";
$strings["version_latest"] = "Ostatni� wersj� jest";
$strings["sourceforge_link"] = "Zobacz stron� projektu na Sourceforge";
$strings["demo_mode"] = "Tryb Demo. Nie ma mo�liwo�ci wykonywania �adnych akcji";
$strings["setup_erase"] = "Skasuj setup.php!!";
$strings["no_file"] = "Nie wybra�e� �adnego pliku";
$strings["exceed_size"] = "Przekroczono maksymaln� wielko�� pliku";
$strings["no_php"] = "plik Php nie mo�e by� umieszczony";
$strings["approval_date"] = "Data zatwierdzenia";
$strings["approver"] = "Zatwierdzaj�cy";

$strings["error_database"] = "Can't connect to database";
$strings["error_server"] = "Can't connect to server";
$strings["version_control"] = "Version Control";
$strings["vc_status"] = "Status";
$strings["vc_last_in"] = "Date last modified";
$strings["ifa_comments"] = "Approval comments";
$strings["ifa_command"] = "Change approval status";
$strings["vc_version"] = "Version";
$strings["ifc_revisions"] = "Peer Reviews";
$strings["ifc_revision_of"] = "Review of version";
$strings["ifc_add_revision"] = "Add Peer Review";
$strings["ifc_update_file"] = "Update file";
$strings["ifc_last_date"] = "Date last modified";
$strings["ifc_version_history"] = "Version History";
$strings["ifc_delete_file"] = "Delete file and all child versions & reviews";
$strings["ifc_delete_version"] = "Delete Selected Version";
$strings["ifc_delete_review"] = "Delete Selected Review";
$strings["ifc_no_revisions"] = "There are currently no revisions of this document";
$strings["unlink_files"] = "Usuwanie plik�w";
$strings["remove_team"] = "Remove Team Members";
$strings["remove_team_info"] = "Remove these users from the project team?";
$strings["remove_team_client"] = "Remove Permission to View Project Site";
$strings["note"] = "Note";
$strings["notes"] = "Notes";
$strings["subject"] = "Subject";
$strings["delete_note"] = "Delete Notes Entries";
$strings["add_note"] = "Add Note Entry";
$strings["edit_note"] = "Edit Note Entry";
$strings["version_increm"] = "Select the version change to apply:";
$strings["url_dev"] = "Development site url";
$strings["url_prod"] = "Final site url";
$strings["note_owner"] = "You can make changes only on your own notes.";
$strings["alpha_only"] = "Alpha-numeric only in login";
$strings["edit_notifications"] = "Edit E-mail Notifications";
$strings["edit_notifications_info"] = "Select events for which you wish to receive E-mail notification.";
$strings["select_deselect"] = "Select/Deselect All";
$strings["noti_addprojectteam1"] = "Added to project team :";
$strings["noti_addprojectteam2"] = "You have been added to the project team for :";
$strings["noti_removeprojectteam1"] = "Removed from project team :";
$strings["noti_removeprojectteam2"] = "You have been removed from the project team for :";
$strings["noti_newpost1"] = "New post :";
$strings["noti_newpost2"] = "A post was added to the following discussion :";
$strings["edit_noti_taskassignment"] = "I am assigned to a new task.";
$strings["edit_noti_statustaskchange"] = "The status of one of my tasks changes.";
$strings["edit_noti_prioritytaskchange"] = "The priority of one of my tasks changes.";
$strings["edit_noti_duedatetaskchange"] = "The due date of one of my tasks changes.";
$strings["edit_noti_addprojectteam"] = "I am added to a project team.";
$strings["edit_noti_removeprojectteam"] = "I am removed from a project team.";
$strings["edit_noti_newpost"] = "A new post is made to a discussion.";
$strings["add_optional"] = "Add an optional";
$strings["assignment_comment_info"] = "Add comments about the assignment of this task";
$strings["my_notes"] = "My Notes";
$strings["edit_settings"] = "Edit settings";
$strings["max_upload"] = "Max file size";
$strings["project_folder_size"] = "Project folder size";
$strings["calendar"] = "Calendar";
$strings["date_start"] = "Start date";
$strings["date_end"] = "End date";
$strings["time_start"] = "Start time";
$strings["time_end"] = "End time";
$strings["calendar_reminder"] = "Reminder";
$strings["shortname"] = "Short name";
$strings["calendar_recurring"] = "Event recurs every week on this day";
$strings["edit_database"] = "Edit database";
$strings["noti_newtopic1"] = "New discussion :";
$strings["noti_newtopic2"] = "A new discussion was added to the following project :";
$strings["edit_noti_newtopic"] = "A new discussion topic was created.";
$strings["today"] = "Today";
$strings["previous"] = "Previous";
$strings["next"] = "Next";
$strings["help"] = "Help";
$strings["complete_date"] = "Complete date";
$strings["scope_creep"] = "Scope creep";
$strings["days"] = "Days";
$strings["logo"] = "Logo";
$strings["remember_password"] = "Remember Password";
$strings["client_add_task_note"] = "Note: The entered task is registered into the data base, appears here however only if it one assigned to a team member!";
$strings["noti_clientaddtask1"] = "Task added by client :";
$strings["noti_clientaddtask2"] = "A new task was added by client from project site to the following project :";
$strings["phase"] = "Phase";
$strings["phases"] = "Phases";
$strings["phase_id"] = "Phase ID";
$strings["current_phase"] = "Active phase(s)";
$strings["total_tasks"] = "Total Tasks";
$strings["uncomplete_tasks"] = "Uncompleted Tasks";
$strings["no_current_phase"] = "No phase is currently active";
$strings["true"] = "True";
$strings["false"] = "False";
$strings["enable_phases"] = "Enable Phases";
$strings["phase_enabled"] = "Phase Enabled";
$strings["order"] = "Order";
$strings["options"] = "Options";
$strings["support"] = "Support";
$strings["support_request"] = "Support Request";
$strings["support_requests"] = "Support Requests";
$strings["support_id"] = "Request ID";
$strings["my_support_request"] = "My Support Requests";
$strings["introduction"] = "Introduction";
$strings["submit"] = "Submit";
$strings["support_management"] = "Support Management";
$strings["date_open"] = "Date Opened";
$strings["date_close"] = "Date Closed";
$strings["add_support_request"] = "Add Support Request";
$strings["add_support_response"] = "Add Support Response";
$strings["respond"] = "Respond";
$strings["delete_support_request"] = "Support request deleted";
$strings["delete_request"] = "Delete support request";
$strings["delete_support_post"] = "Delete support post";
$strings["new_requests"] = "New requests";
$strings["open_requests"] = "Open requests";
$strings["closed_requests"] = "Complete requests";
$strings["manage_new_requests"] = "Manage new requests";
$strings["manage_open_requests"] = "Manage open requests";
$strings["manage_closed_requests"] = "Manage complete requests";
$strings["responses"] = "Responses";
$strings["edit_status"] = "Edit Status";
$strings["noti_support_request_new2"] = "You have submited a support request regarding: ";
$strings["noti_support_post2"] = "A new response has been added to your support request. Please review the details bellow.";
$strings["noti_support_status2"] = "Your support request has been updated. Please review the details bellow.";
$strings["noti_support_team_new2"] = "A new support request has been added to project: ";
//2.0
$strings["delete_subtasks"] = "Delete subtasks";
$strings["add_subtask"] = "Add subtask";
$strings["edit_subtask"] = "Edit subtask";
$strings["subtask"] = "Subtask";
$strings["subtasks"] = "Subtasks";
$strings["show_details"] = "Show details";
$strings["updates_task"] = "Task update history";
$strings["updates_subtask"] = "Subtask update history";
//2.1
$strings["go_projects_site"] = "Go to projects site";
$strings["bookmark"] = "Bookmark";
$strings["bookmarks"] = "Bookmarks";
$strings["bookmark_category"] = "Category";
$strings["bookmark_category_new"] = "New category";
$strings["bookmarks_all"] = "All";
$strings["bookmarks_my"] = "My Bookmarks";
$strings["my"] = "My";
$strings["bookmarks_private"] = "Private";
$strings["shared"] = "Shared";
$strings["private"] = "Private";
$strings["add_bookmark"] = "Add bookmark";
$strings["edit_bookmark"] = "Edit bookmark";
$strings["delete_bookmarks"] = "Delete bookmarks";
$strings["team_subtask_details"] = "Team Subtask Details";
$strings["client_subtask_details"] = "Client Subtask Details";
$strings["client_change_status_subtask"] = "Change your status below when you have completed this subtask";
$strings["disabled_permissions"] = "Disabled account";
$strings["user_timezone"] = "Timezone (GMT)";
//2.2
$strings["project_manager_administrator_permissions"] = "Project Manager Administrator";
$strings["bug"] = "Bug Tracking";
//2.3
$strings["report"] = "Report";
$strings["license"] = "License";
//2.4
$strings["settings_notwritable"] = "Settings.php file is not writable";
?>